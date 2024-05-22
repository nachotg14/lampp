function verificarSesionYAbrirModal() {
    // Verificar si el usuario ha iniciado sesión
    var cookieId = getCookie("cookie_id");
    if (cookieId) {
        // Si el usuario ha iniciado sesión, abrir el modal para añadir comentario
        $('#modalComentario').modal('show');
    } else {
        // Si el usuario no ha iniciado sesión, mostrar un mensaje
        alert("Debes iniciar sesión para añadir una noticia.");
    }
}

function abrirModalComentario(id_noticia) {
    // Verificar si el usuario ha iniciado sesión
    var cookieId = getCookie("cookie_id");
    if (cookieId) {
        // Si el usuario ha iniciado sesión, establecer el ID de la noticia en el formulario de comentario y abrir el modal
        $('#id_noticia_comentario').val(id_noticia);
        $('#modalComentario').modal('show');
    } else {
        // Si el usuario no ha iniciado sesión, mostrar un mensaje
        alert("Debes iniciar sesión para añadir un comentario.");
    }
}

function borrarCookie() {
    // Establecer la fecha de expiración en el pasado
    document.cookie = 'cookie_id=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
    // Recargar la página para reflejar los cambios
    location.reload();
}

function guardarNoticia() {
    var formData = new FormData(document.getElementById("formComentario"));
    // Agregar el id del usuario desde la cookie
    formData.append("id_usuario", getCookie("cookie_id"));

    $.ajax({
        type: "POST",
        url: "guardar_comentario.php",
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            alert(response); // Muestra la respuesta del servidor (puedes personalizarlo)
            $('#modalComentario').modal('hide');
        },
        error: function(xhr, status, error) {
            alert("Error al guardar el comentario: " + error); // Muestra un mensaje de error si hay algún problema
        }
    });
}

function guardarComentario() {
    var formData = new FormData(document.getElementById("formComentario"));
    // Agregar el id del usuario desde la cookie
    formData.append("id_usuario", getCookie("cookie_id"));

    $.ajax({
        type: "POST",
        url: "guardar_comentario.php",
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            alert(response); // Muestra la respuesta del servidor (puedes personalizarlo)
            $('#modalComentario').modal('hide');
        },
        error: function(xhr, status, error) {
            alert("Error al guardar el comentario: " + error); // Muestra un mensaje de error si hay algún problema
        }
    });
}

// Función para obtener el valor de una cookie por su nombre
function getCookie(name) {
    var cookieArr = document.cookie.split(";");
    for (var i = 0; i < cookieArr.length; i++) {
        var cookiePair = cookieArr[i].split("=");
        if (name == cookiePair[0].trim()) {
            return decodeURIComponent(cookiePair[1]);
        }
    }
    return null;
}

