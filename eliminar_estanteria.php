<?php
session_start();
//se verifica que el usuario ha iniciado sesion
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

require_once 'db.php';
//se obtiene id desde el url
$id = $_GET['id'] ?? null;
if (!$id) {
    echo "ID no válido.";
    exit();
}
//se prepara una consulta para eliminar una estanteria
$stmt = $conn->prepare("DELETE FROM estantes WHERE id = ?");
$stmt->bind_param("i", $id);
if ($stmt->execute()) {
    header("Location: biblioteca.php?mensaje=eliminado");
    exit();
} else {
    echo "Error al eliminar: " . $conn->error; //muestra un mensaje si no se ejecuta correctamente
}
?>