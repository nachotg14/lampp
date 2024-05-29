<?php
session_start();

// Datos de conexión a la base de datos
include_once 'conexion.php';

// Establecer conexión con la base de datos
try {
    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Verificar si el usuario ha iniciado sesión
if (!isset($_COOKIE['cookie_id'])) {
    header("Location: iniciarsesion.html");
    exit();
}

// Obtener el ID del usuario desde la cookie
$user_id = htmlspecialchars($_COOKIE['cookie_id'], ENT_QUOTES, 'UTF-8');

// Consultar la información del usuario
$obtener_datos = "SELECT * FROM usuarios WHERE id = :id";
$stmt = $conn->prepare($obtener_datos);
$stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Variable para el mensaje de éxito
$success_message = "";

// Procesar el formulario cuando se envía
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['correo_electronico'])) {
        $user['correo_electronico'] = htmlspecialchars($_POST['correo_electronico'], ENT_QUOTES, 'UTF-8');
        $actualizar_correo = "UPDATE usuarios SET correo_electronico = :correo_electronico WHERE id = :id";
        $stmt = $conn->prepare($actualizar_correo);
        $stmt->bindParam(':correo_electronico', $user['correo_electronico'], PDO::PARAM_STR);
        $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $success_message = "Datos guardados correctamente.";
    }

    if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] == UPLOAD_ERR_OK) {
        $carpeta_destino = 'uploads/';
        $archivo_destino = $carpeta_destino . basename($_FILES['foto_perfil']['name']);
        if (move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $archivo_destino)) {
            $user['foto_perfil'] = htmlspecialchars($archivo_destino, ENT_QUOTES, 'UTF-8');
            $actualizar_foto = "UPDATE usuarios SET foto_perfil = :foto_perfil WHERE id = :id";
            $stmt = $conn->prepare($actualizar_foto);
            $stmt->bindParam(':foto_perfil', $user['foto_perfil'], PDO::PARAM_STR);
            $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            $success_message = "Datos guardados correctamente.";
        }
    }

    if (isset($_POST['current_password']) && isset($_POST['new_password']) && isset($_POST['confirm_password'])) {
        $current_password = hash('sha256', $_POST['current_password']);
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        // Verificar si la contraseña actual es correcta
        if ($current_password == $user['password']) {
            // Verificar si la nueva contraseña cumple con los criterios de seguridad
            if ($new_password == $confirm_password) {
                if (
                    strlen($new_password) >= 12 &&
                    preg_match('/[A-Z]/', $new_password) &&
                    preg_match('/[a-z]/', $new_password) &&
                    preg_match('/[0-9]/', $new_password) &&
                    preg_match('/[^\w]/', $new_password)
                ) {

                    $new_password_hashed = hash('sha256', $new_password);
                    $actualizar_password = "UPDATE usuarios SET password = :password WHERE id = :id";
                    $stmt = $conn->prepare($actualizar_password);
                    $stmt->bindParam(':password', $new_password_hashed, PDO::PARAM_STR);
                    $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
                    $stmt->execute();
                    $success_message = "Contraseña actualizada correctamente.";
                } else {
                    $password_message = "La nueva contraseña debe tener al menos 8 caracteres, incluir letras mayúsculas, minúsculas, números y caracteres especiales.";
                }
            } else {
                $password_message = "Las nuevas contraseñas no coinciden.";
            }
        } else {
            $password_message = "La contraseña actual es incorrecta.";
        }
    }
}

// Si no hay foto de perfil, usar la predeterminada
$foto_perfil = $user['foto_perfil'] ? htmlspecialchars($user['foto_perfil'], ENT_QUOTES, 'UTF-8') : 'user-foto.png';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .error {
            color: red;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="index.php" class="btn btn-light">Volver a la Página Principal</a>
            <h2>Perfil de Usuario</h2>
        </div>
        <?php if ($success_message) : ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        <?php if (isset($password_message)) : ?>
            <div class="alert alert-info"><?php echo $password_message; ?></div>
        <?php endif; ?>
        <form method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="user">Nombre de Usuario:</label>
                <input type="text" class="form-control" id="user" name="user" value="<?php echo htmlspecialchars($user['user']); ?>" disabled>
            </div>
            <div class="form-group">
                <label for="dni">DNI:</label>
                <input type="text" class="form-control" id="dni" name="dni" value="<?php echo htmlspecialchars($user['dni']); ?>" disabled>
            </div>
            <div class="form-group">
                <label for="correo_electronico">Correo Electrónico:</label>
                <input type="email" class="form-control" id="correo_electronico" name="correo_electronico" value="<?php echo htmlspecialchars($user['correo_electronico']); ?>" required>
            </div>
            <div class="form-group">
                <label for="foto_perfil">Foto de Perfil:</label>
                <div class="d-flex align-items-center">
                    <img src="<?php echo htmlspecialchars($foto_perfil); ?>" alt="Foto de Perfil" class="rounded-circle" width="100" height="100">
                    <input type="file" class="form-control-file ml-3" id="foto_perfil" name="foto_perfil">
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        </form>
        <button type="button" class="btn btn-warning mt-3" data-toggle="modal" data-target="#modalCambiarContrasena">Cambiar Contraseña</button>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalCambiarContrasena" tabindex="-1" aria-labelledby="modalCambiarContrasenaLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="current_password">Contraseña Actual:</label>
                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                        </div>
                        <div class="form-group">
                            <label for="new_password">Nueva Contraseña:</label>
                            <input type="password" class="form-control" id="new_password" name="new_password" oninput="validarPassword()" required>
                            <div id="passError" class="error"></div>
                        </div>
                        <div class="form-group">
                            <label for="confirm_password">Confirmar Nueva Contraseña:</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" oninput="confirmarCoincidencia()" required>
                            <div id="confirmPassError" class="error"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Cambiar Contraseña</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="index.js"></script>
</body>

</html>