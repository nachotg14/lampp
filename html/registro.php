<?php
// Datos de conexión a la base de datos
$servername = "db";
$username = "root";
$password = "root_password";
$database = "lampdb";

// Obtener datos del formulario
$user = $_POST['username'];
$email = $_POST['email'];
$pass = $_POST['password'];
$dni = $_POST['dni'];

// Convertir la contraseña a SHA-256
$pass_sha256 = hash('sha256', $pass);

// Verificar la letra del DNI
if (validarDNI($dni)) {
    echo "La letra del DNI es correcta.";
} else {
    echo "La letra del DNI no es correcta.";
    return; // Salir del script si la letra del DNI no es correcta
}

try {
    // Establecer conexión con la base de datos
    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    // Establecer el modo de error PDO a excepción
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Preparar la sentencia SQL para la inserción
    $stmt = $conn->prepare("INSERT INTO usuarios (user, correo_electronico, password, dni) VALUES (:user, :correo_electronico, :password, :dni)");
    // Vincular parámetros
    $stmt->bindParam(':user', $user);
    $stmt->bindParam(':correo_electronico', $email);
    $stmt->bindParam(':password', $pass_sha256);
    $stmt->bindParam(':dni', $dni);

    // Ejecutar la sentencia
    $stmt->execute();

    // Redireccionar a otra página

} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}

function validarDNI($dni_str) {
    // Eliminar espacios en blanco y convertir a mayúsculas
    $dni_str = strtoupper(str_replace(' ', '', $dni_str));

    // Extraer el número y la letra del DNI
    $numero = substr($dni_str, 0, -1);
    $letra_introducida = substr($dni_str, -1);

    // Array con las letras posibles del DNI
    $letras = 'TRWAGMYFPDXBNJZSQVHLCKE';
    $letra_correcta = $letras[$numero % 23];

    // Verificar si la letra introducida es correcta
    return $letra_correcta === $letra_introducida;
}

echo '<script>window.location.href = "./index.php";</script>';
exit; // Asegura que el script se detenga después de la redirección

?>
