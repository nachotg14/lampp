<?php
include_once './conexion.php';
// Código PHP para eliminar la cookie de sesión
if (isset($_COOKIE['cookie_id'])) {
    setcookie('cookie_id', '',time()-3600 , '/');
    header("Location: ../index.php");
    exit;
}else{
    header("Location: ../index.php");
    exit;
}