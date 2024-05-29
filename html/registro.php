<?php
include_once './Funciones.php';
include_once './conexion.php';

// Datos de conexión a la base de datos

// Obtener datos del formulario
$user = $_POST['username'];
$email = $_POST['email'];
$pass = $_POST['password'];
$dni = $_POST['dni'];

// Convertir la contraseña a SHA-256
$pass_sha256 = hash('sha256', $pass);

// Verificar la letra del DNI
if (Funciones::validarDNI($dni)) {
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



echo '<script>window.location.href = "./index.php";</script>';
exit; // Asegura que el script se detenga después de la redirección

?>