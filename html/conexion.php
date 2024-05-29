<?php
        // Datos de conexión a la base de datos
        // $servername = "db";
        // $username = "root";
        // $password = "root_password";
        // $database = "lampdb";
        // $port="";
       $servername = "localhost";
       $username = "id22241549_nachotg14";
       $password = "Elmolino1414.";
       $database = "id22241549_lampdb";
       $port="3306";


// Establecer conexión con la base de datos y otras variables necesarias

try {
    $conn = new PDO("mysql:host=$servername;dbname=$database;port=$port", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

}
catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>