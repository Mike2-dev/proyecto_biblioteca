<?php
session_start(); 

// Verifica si el usuario está autenticado
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php"); // Si no lo esta, redirige al login
    exit();
}

require_once 'db.php';

// Obtiene el ID del libro desde la URL usando GET
$id = $_GET['id'] ?? null; 

// Si no se recibe un ID válido, muestra un mensaje 
if (!$id) {
    echo "ID no válido.";
    exit();
}

// Prepara la consulta SQL para eliminar el libro con el ID recibido
$stmt = $conn->prepare("DELETE FROM libros WHERE id = ? AND usuario_id = ?");
$stmt->bind_param("ii", $id, $_SESSION['usuario']['id']); 

// Ejecuta la consulta y redirige a la biblioteca 
if ($stmt->execute()) {
    header("Location: biblioteca.php?mensaje=eliminado"); 
    exit();
} else {
    // Si ocurre un error en la ejecución, lo muestra
    echo "Error al eliminar: " . $conn->error;
}
?>
