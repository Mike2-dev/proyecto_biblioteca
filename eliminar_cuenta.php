<?php
session_start(); 
require_once 'db.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}
//se obtiene la id del usuario desde la sesion 
$usuario_id = $_SESSION['usuario']['id'];
// se prepara la consulta para eliminar libros
$stmt = $conn->prepare("DELETE FROM libros WHERE usuario_id = ?");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$stmt->close();
//se prepara la consulta para eliminar estantes 
$stmt = $conn->prepare("DELETE FROM estantes WHERE usuario_id = ?");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$stmt->close();
//se prepara la consulta para eliminar al usuario
$stmt = $conn->prepare("DELETE FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$stmt->close();
// Destruye la sesión
session_destroy();

header("Location: index.php");
exit();
?>
