<?php
include 'conexion.php';

// Verificar si el usuario ha iniciado sesión
if (isset($_COOKIE['cookie_id'])) {
    try {
        // Obtener los datos del formulario
        $titulo = $_POST['titulo'];
        $contenido = $_POST['contenido_noticia'];
        $idUsuario = $_COOKIE['cookie_id'];


        // Procesar la imagen adjunta


        // Directorio donde se almacenarán las imágenes
        $directorio = "./imagenes/";

        // Verificar si se cargó una imagen
        if (isset($_FILES['imagen']) && $_FILES['imagen']['size'] > 0) {
            $nombreArchivo = $_FILES['imagen']['name'];
            $tipoArchivo = $_FILES['imagen']['type'];
            $tamañoArchivo = $_FILES['imagen']['size'];
            $tmpName = $_FILES['imagen']['tmp_name'];
            $guardarImagen = file_get_contents($tmpName);
            $stmt = $conn->prepare("INSERT INTO noticias (titulo_noticia, contenido_noticia, fecha_noticia, id_usuario, imagen_noticia) VALUES (:titulo, :contenido, NOW(), :id_usuario, :imagen)");
            $stmt->bindParam(':titulo', $titulo);
            $stmt->bindParam(':contenido', $contenido);
            $stmt->bindParam(':id_usuario', $idUsuario);
            $stmt->bindParam(':imagen', $guardarImagen, PDO::PARAM_LOB);
            $stmt->execute();
        } else {

            $stmt = $conn->prepare("INSERT INTO noticias (titulo_noticia, contenido_noticia, fecha_noticia, id_usuario) VALUES (:titulo, :contenido, NOW(), :id_usuario)");
            $stmt->bindParam(':titulo', $titulo);
            $stmt->bindParam(':contenido', $contenido);
            $stmt->bindParam(':id_usuario', $idUsuario);
            $stmt->execute();
        }

        // Enviar la notificación de éxito al cliente
        echo '<script>window.location.href="index.php"</script>';

    } catch (PDOException $e) {
        echo "Error al guardar la noticia: " . $e->getMessage();
    }
} else {
    // Si el usuario no ha iniciado sesión, mostrar un mensaje y no procesar el formulario
    echo "Debes iniciar sesión para enviar una noticia.";
}
?>