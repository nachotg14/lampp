<?php
header('Content-Type: application/json');

$response = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["contenido_comentario"]) && isset($_POST["id_noticia_comentario"])) {
        include 'conexion.php';

        $contenido_comentario = $_POST["contenido_comentario"];
        $id_noticia_comentario = $_POST["id_noticia_comentario"];
        $id_usuario = $_COOKIE['cookie_id']; // Suponiendo que tienes el ID del usuario en una cookie

        $sql = "INSERT INTO comentarios_noticias (id_noticia, id_usuario, contenido_comentario, fecha_comentario) VALUES (:id_noticia, :id_usuario, :contenido_comentario, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id_noticia', $id_noticia_comentario);
        $stmt->bindParam(':id_usuario', $id_usuario);
        $stmt->bindParam(':contenido_comentario', $contenido_comentario);

        if ($stmt->execute()) {
            $response['status'] = 'success';
            $response['message'] = 'Comentario guardado correctamente.';
        } else {
            $response['status'] = 'error';
            $response['message'] = 'No se pudo guardar el comentario.';
        }
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Faltan datos.';
    }
} else {
    $response['status'] = 'error';
    $response['message'] = 'Solicitud inválida.';
}

echo json_encode($response);
?>