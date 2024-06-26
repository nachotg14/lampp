<?php
// Datos de conexión a la base de datos
include_once 'conexion.php';

// Obtener datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8');
    $pass = htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8');


    try {
        // Establecer conexión con la base de datos
        $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
        // Establecer el modo de error PDO a excepción
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Preparar la sentencia SQL para verificar los datos del usuario
        $stmt = $conn->prepare("SELECT * FROM usuarios WHERE user = :user AND password = :password");
        // Vincular parámetros
        $stmt->bindParam(':user', $user);
        // Convertir la contraseña a SHA-256 antes de la comparación
        $pass_sha256 = hash('sha256', $pass);
        $stmt->bindParam(':password', $pass_sha256);

        // Ejecutar la sentencia
        $stmt->execute();

        // Verificar si se encontró un usuario con las credenciales proporcionadas
        if ($stmt->rowCount() == 1) {
            // Obtener el ID del usuario
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $user_id = $row['id'];

            // Iniciar sesión (aquí puedes establecer tus variables de sesión, etc.)
            session_start();
            $_SESSION['username'] = $user;

            // Crear una cookie de sesión con el ID del usuario
            setcookie("cookie_id", $user_id, [
                'expires' => time() + (3600), // 1h
                'path' => '/',
                'domain' => '', // Puedes especificar tu dominio si es necesario
                'secure' => true, // Asegúrate de usar HTTPS
                'httponly' => true,
                'samesite' => 'Strict'
            ]);
            // Redirigir a la página de inicio o a cualquier otra página que desees
            header("Location: index.php");
            exit;
        } else {
            // Si las credenciales son incorrectas, muestra un mensaje de error
            echo "Usuario o contraseña incorrectos.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }

    // Cerrar conexión
    $conn = null;
}
