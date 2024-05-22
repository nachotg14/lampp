<?php
        // Datos de conexión a la base de datos
        $servername = "db";
        $username = "root";
        $password = "root_password";
        $database = "lampdb";


// Establecer conexión con la base de datos y otras variables necesarias

try {
    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

}
catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>