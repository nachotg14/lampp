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
//FUNCION OWASP
document.addEventListener('DOMContentLoaded', function () {
    const passwordField = document.getElementById("new_password");
    const passError = document.getElementById("passError");
    const submitBtn = document.querySelector('form');

    passwordField.addEventListener('input', validarPassword);

    function validarPassword() {
        const password = passwordField.value;
        passError.innerHTML = '';

        const requirements = [
            { regex: /[a-z]/, message: "No cumple con minúsculas." },
            { regex: /[A-Z]/, message: "No cumple con mayúsculas." },
            { regex: /\d/, message: "No tiene números." },
            { regex: /[@$!%*?&]/, message: "No tiene símbolos." },
            { regex: /.{12,}/, message: "Debe tener al menos 12 caracteres." }
        ];

        let isValid = true;

        requirements.forEach(requirement => {
            if (!requirement.regex.test(password)) {
                const li = document.createElement('li');
                li.textContent = requirement.message;
                passError.appendChild(li);
                isValid = false;
            }
        });

        if (isValid) {
            submitBtn.removeAttribute('disabled');
        } else {
            submitBtn.setAttribute('disabled', 'disabled');
        }
    }
});

function validarPassword() {
    const newPassword = document.getElementById("new_password").value;
    const confirmarPassword = document.getElementById("confirm_password").value;
    const passError = document.getElementById("passError");
    const confirmPassError = document.getElementById("confirmPassError");
    const submitBtn = document.querySelector("button[type='submit']");

    const errors = [];
    if (!/[A-Z]/.test(newPassword)) {
        errors.push("No cumple con mayúsculas.");
    }
    if (!/[a-z]/.test(newPassword)) {
        errors.push("No cumple con minúsculas.");
    }
    if (!/\d/.test(newPassword)) {
        errors.push("No tiene números.");
    }
    if (!/[@$!%*?&]/.test(newPassword)) {
        errors.push("No tiene símbolos.");
    }
    if (newPassword.length < 12) {
        errors.push("Debe tener al menos 12 caracteres.");
    }

    

    if (errors.length === 0) {
        passError.textContent = "";
        passError.classList.remove("error");
        submitBtn.disabled = false;
    } else {
        passError.innerHTML = errors.join("<br>");
        passError.classList.add("error");
        submitBtn.disabled = true;
    }
}

function confirmarCoincidencia() {
    const newPassword = document.getElementById("new_password").value;
    const confirmarPassword = document.getElementById("confirm_password").value;
    const confirmPassError = document.getElementById("confirmPassError");
    const submitBtn = document.querySelector("button[type='submit']");

    if (newPassword !== confirmarPassword) {
        confirmPassError.textContent = "Las contraseñas no coinciden.";
        confirmPassError.classList.add("error");
        submitBtn.disabled = true;
    } else {
        confirmPassError.textContent = "";
        confirmPassError.classList.remove("error");
        submitBtn.disabled = false;
    }
}