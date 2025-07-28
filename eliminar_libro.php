<?php
session_start(); 
require_once 'db.php';
// Verifica si el usuario está autenticado
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php"); // Si no lo esta, redirige al login
    exit();
}

// Obtiene el ID del libro desde la URL usando GET
$id = $_GET['id'] ?? null; 

// Si no se recibe un ID válido, muestra un mensaje 
if (!$id) {
    echo "ID no válido.";
    exit();
}
//se elimina la portada si se guardo en el servidor
$stmt = $conn->prepare("SELECT portada FROM libros WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($portada);
$stmt->fetch();
$stmt->close();

if (!empty($portada) && !filter_var($portada, FILTER_VALIDATE_URL) && $portada !== 'imagenes/iconos/icono_portada.avif'
) {
    if (file_exists($portada)) {
        unlink($portada);
    }
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
