<?php
include 'conexion.php';

// Verificar si el usuario ha iniciado sesión
if (isset($_COOKIE['cookie_id'])) {
    try {
        // Sanitize POST inputs
        $titulo = htmlspecialchars($_POST['titulo'], ENT_QUOTES, 'UTF-8');
        $contenido = htmlspecialchars($_POST['contenido_noticia'], ENT_QUOTES, 'UTF-8');
        $idUsuario = htmlspecialchars($_COOKIE['cookie_id'], ENT_QUOTES, 'UTF-8');

        // Verificar si se cargó una imagen
        if (isset($_FILES['imagen']) && $_FILES['imagen']['size'] > 0) {
            // Procesar la imagen adjunta
            $nombreArchivo = $_FILES['imagen']['name'];
            $tipoArchivo = $_FILES['imagen']['type'];
            $tamañoArchivo = $_FILES['imagen']['size'];
            $tmpName = $_FILES['imagen']['tmp_name'];
            $guardarImagen = file_get_contents($tmpName);

            $stmt = $conn->prepare("INSERT INTO noticias (titulo_noticia, contenido_noticia, fecha_noticia, id_usuario, imagen_noticia) VALUES (:titulo, :contenido, NOW(), :id_usuario, :imagen)");
            $stmt->bindParam(':titulo', $titulo, PDO::PARAM_STR);
            $stmt->bindParam(':contenido', $contenido, PDO::PARAM_STR);
            $stmt->bindParam(':id_usuario', $idUsuario, PDO::PARAM_INT);
            $stmt->bindParam(':imagen', $guardarImagen, PDO::PARAM_LOB);
            $stmt->execute();
        } else {
            $stmt = $conn->prepare("INSERT INTO noticias (titulo_noticia, contenido_noticia, fecha_noticia, id_usuario) VALUES (:titulo, :contenido, NOW(), :id_usuario)");
            $stmt->bindParam(':titulo', $titulo, PDO::PARAM_STR);
            $stmt->bindParam(':contenido', $contenido, PDO::PARAM_STR);
            $stmt->bindParam(':id_usuario', $idUsuario, PDO::PARAM_INT);
            $stmt->execute();
        }

        // Enviar la notificación de éxito al cliente
        echo '<script>window.location.href="index.php"</script>';

    } catch (PDOException $e) {
        echo "Error al guardar la noticia: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
    }
} else {
    // Si el usuario no ha iniciado sesión, mostrar un mensaje y no procesar el formulario
    echo "Debes iniciar sesión para enviar una noticia.";
}
?>
