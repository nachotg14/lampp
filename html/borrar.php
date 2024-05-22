<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="index.css">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <style>
        /* Estilos para ajustar la posición del botón de añadir noticia */
        #btn-add-noticia {
            margin-bottom: 5mm;
        }

        /* Estilos para separar los botones de iniciar sesión y registro */
        .btn-group {
            margin-bottom: 5mm;
        }
    </style>
</head>

<body>
    <header class="bg-dark text-white d-flex justify-content-between align-items-center px-3 py-2">
        <!-- Logo -->
        <img src="ruta-del-logo.jpg" alt="logo" style="width: 100px;">
        <!-- Cambia "ruta-del-logo.jpg" por la ruta de tu imagen -->
        <?php
        include 'conexion.php';

        if (isset($_COOKIE['cookie_id'])) {
            $user_id = $_COOKIE['cookie_id'];
            $obtener_datos="SELECT * FROM usuarios WHERE id = :id";
            $stmt = $conn->prepare($obtener_datos);
            $stmt->bindParam(':id', $user_id);
            $stmt->execute();
            if ($stmt->rowCount() == 1) {
                // Obtener el ID del usuario
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                // Si la variable de sesión 'username' está establecida, mostramos el nombre de usuario
                echo '<div class="dropdown">';
                echo '<button class="btn btn-success dropdown-toggle d-block" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">';
                echo '<img src="./user-foto.png" style="with:50px;height:50px; border-radius:100%;">';
                echo $row['user'];
                echo '</button>';
                echo '<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">';
                echo '<a class="dropdown-item" href="perfil.php">Perfil</a>';
                echo '<a class="dropdown-item" onclick="borrarCookie()">Cerrar Sesión</a>';
                echo '</div>';
                echo '</div>';  
            }
        } 
        else {
            // Si la variable de sesión 'username' no está establecida, mostramos los botones de inicio de sesión y registro
            echo '<div class="btn-group">';
            echo '<a class="btn btn-success" href="iniciarsesion.html">Iniciar Sesión</a>';
            echo '<a class="btn btn-success" href="registro.html">Registro</a>';
            echo '</div>';
        }
        ?>
        
    </header>
    <section class="contenido mt-5 mb-5 animado">
    <?php
        $sql_mensajes = "SELECT u.user AS nombre_usuario, u.foto_perfil, n.id_noticia, n.titulo_noticia, n.contenido_noticia, n.fecha_noticia 
                         FROM noticias n
                         JOIN usuarios u ON n.id_usuario = u.id";
        $result = $conn->query($sql_mensajes);

        if ($result->rowCount() > 0) {
            while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                echo '<div class="noticia-container" noticia-id="'.$row['id_noticia'].'">';
                echo '<div class="perfil">';
                if (empty($row['foto_perfil'])) {
                    echo '<img src="./user-foto.png" alt="Foto de perfil por defecto" width="50" height="50">';
                } else {
                    echo '<img src="data:image/jpeg;base64,' . base64_encode($row['foto_perfil']) . '" alt="Foto de perfil" width="50" height="50">';
                }                
                echo '<span>' . htmlspecialchars($row['nombre_usuario']) . '</span>';
                echo '</div>';
                echo '<h3>' . htmlspecialchars($row['titulo_noticia']) . '</h3>';
                echo '<p class="contenido">' . htmlspecialchars($row['contenido_noticia']) . '</p>';
                echo '<div class="fecha">' . htmlspecialchars($row['fecha_noticia']) . '</div>';
                // Botón de añadir comentario
                echo '<button id="btn-add-noticia" class="btn btn-primary btn-comentario" onclick="abrirModalComentario(' . $row['id_noticia'] . ')">Añadir Comentario</button>';
                echo '</div>';

            }
        } else {
            echo '<p>No hay noticias disponibles.</p>';
        }
    ?>
    </section>
    <footer class="footer bg-dark ">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <ul class="nav justify-content-center">
                        <li class="nav-item"><a href="#" class="nav-link text-white">Home</a></li>
                        <li class="nav-item"><a href="#" class="nav-link text-white">Features</a></li>
                        <li class="nav-item"><a href="#" class="nav-link text-white">Pricing</a></li>
                        <li class="nav-item"><a href="#" class="nav-link text-white">FAQs</a></li>
                        <li class="nav-item"><a href="#" class="nav-link text-white">About</a></li>
                    </ul>
                    <p class="text-muted">© 2022 Company, Inc</p>
                </div>
            </div>
        </div>
    </footer>
<!-- Modal para añadir noticia -->
<div class="modal fade" id="modalNoticia" tabindex="-1" aria-labelledby="modalNoticiaLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalNoticiaLabel">Añadir Noticia</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="formNoticia" method="post" action="guardar_noticia.php">
          <div class="form-group">
            <label for="titulo">Título:</label>
            <input type="text" class="form-control" id="titulo" name="titulo">
          </div>
          <div class="form-group">
            <label for="contenido_noticia">Contenido:</label>
            <textarea class="form-control" id="contenido_noticia" name="contenido_noticia" rows="3"></textarea>
          </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>


<!-- Modal para añadir comentario -->
<div class="modal fade" id="modalComentario" tabindex="-1" aria-labelledby="modalComentarioLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalComentarioLabel">Añadir Comentario</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="formComentario" action="/guardar_comentario.php" method="POST">
          <div class="form-group">
            <label for="contenido_comentario">Comentario:</label>
            <textarea class="form-control" id="contenido_comentario" name="contenido_comentario" rows="3"></textarea>
          </div>
          <input type="hidden" id="id_noticia_comentario" name="id_noticia_comentario" value="">
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            <button type="button" class="btn btn-primary" onclick="guardarComentario()">Guardar</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<button id="btn-add-noticia" class="btn btn-primary fijado" data-toggle="modal" data-target="#modalNoticia">Añadir Noticia</button>

    <!-- Bootstrap JS y jQuery (opcional) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="index.js"></script>
</body>

</html>
