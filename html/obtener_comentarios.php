<?php
include 'conexion.php';

if (isset($_GET['id_noticia'])) {
    // Aplicar htmlspecialchars a la entrada GET
    $id_noticia = htmlspecialchars($_GET['id_noticia'], ENT_QUOTES, 'UTF-8');

    $sql_comentarios = "SELECT c.id_comentario, c.contenido_comentario, c.fecha_comentario, u.user 
                        FROM comentarios_noticias c
                        JOIN usuarios u ON c.id_usuario = u.id
                        WHERE c.id_noticia = :id_noticia
                        ORDER BY c.fecha_comentario DESC";
    $stmt = $conn->prepare($sql_comentarios);
    $stmt->bindParam(':id_noticia', $id_noticia, PDO::PARAM_INT);
    $stmt->execute();
    $comentarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Escapar datos antes de enviarlos como JSON
    foreach ($comentarios as &$comentario) {
        $comentario['id_comentario'] = htmlspecialchars($comentario['id_comentario'], ENT_QUOTES, 'UTF-8');
        $comentario['contenido_comentario'] = htmlspecialchars($comentario['contenido_comentario'], ENT_QUOTES, 'UTF-8');
        $comentario['fecha_comentario'] = htmlspecialchars($comentario['fecha_comentario'], ENT_QUOTES, 'UTF-8');
        $comentario['user'] = htmlspecialchars($comentario['user'], ENT_QUOTES, 'UTF-8');
    }

    echo json_encode($comentarios);
}
?>
