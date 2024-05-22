<?php
include 'conexion.php';

if (isset($_GET['id_noticia'])) {
    $id_noticia = $_GET['id_noticia'];

    $sql_comentarios = "SELECT c.id_comentario, c.contenido_comentario, c.fecha_comentario, u.user 
                        FROM comentarios_noticias c
                        JOIN usuarios u ON c.id_usuario = u.id
                        WHERE c.id_noticia = :id_noticia
                        ORDER BY c.fecha_comentario DESC";
    $stmt = $conn->prepare($sql_comentarios);
    $stmt->bindParam(':id_noticia', $id_noticia);
    $stmt->execute();
    $comentarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($comentarios);
}
?>
