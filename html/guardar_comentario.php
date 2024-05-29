<?php
header('Content-Type: application/json');

$response = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["contenido_comentario"]) && isset($_POST["id_noticia_comentario"])) {
        include 'conexion.php';

        // Sanitize POST inputs
        $contenido_comentario = htmlspecialchars($_POST["contenido_comentario"], ENT_QUOTES, 'UTF-8');
        $id_noticia_comentario = htmlspecialchars($_POST["id_noticia_comentario"], ENT_QUOTES, 'UTF-8');

        // Sanitize cookie input
        $id_usuario = htmlspecialchars($_COOKIE['cookie_id'], ENT_QUOTES, 'UTF-8'); // Suponiendo que tienes el ID del usuario en una cookie

        $sql = "INSERT INTO comentarios_noticias (id_noticia, id_usuario, contenido_comentario, fecha_comentario) VALUES (:id_noticia, :id_usuario, :contenido_comentario, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id_noticia', $id_noticia_comentario, PDO::PARAM_INT);
        $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
        $stmt->bindParam(':contenido_comentario', $contenido_comentario, PDO::PARAM_STR);

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
