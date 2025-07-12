<?php
session_start();
// Verifica si hay una sesión activa del usuario.
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}
// Si la sesión está activa, se destruye
session_destroy();
header("Location:login.php");
exit();
?>