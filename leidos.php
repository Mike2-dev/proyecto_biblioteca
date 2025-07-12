<?php 
session_start(); // Inicia la sesión PHP para mantener al usuario autenticado


//  autenticado en la sesión.
// Verifica si hay un usuario. Si no lo hay, redirige al login.
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

include 'db.php'; 

// Obtiene el ID del usuario actual
$usuario_id = $_SESSION['usuario']['id'];
$estado = 'leido'; //buscar solo los libros que estén marcados como "leído"

// Prepara la consulta SQL para obtener los libros leídos por el usuario actual
$sql = "SELECT id, titulo, portada FROM libros WHERE usuario_id = ? AND estado = ?";
$stmt = $conn->prepare($sql); 
$stmt->bind_param("is", $usuario_id, $estado); 
$stmt->execute(); 
$resultado = $stmt->get_result(); // Obtiene el resultado

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Libros Leídos</title>
    <link rel="stylesheet" href="style.css"> 
</head>
<body class="libros">
    <div class="titulo">
        <h1> Libros Leídos</h1> 
    </div>

    <div class="contenedor-libros">
        <!-- Bucle que recorre cada libro obtenido de la base de datos -->
        <?php while ($libro = $resultado->fetch_assoc()): ?> 
            <!-- Cada libro se presenta como un enlace que lleva a su página de detalles -->
            <a href="detalles.php?id=<?= $libro['id'] ?>" class="libro">
                <?php if (!empty($libro['portada'])): ?>
                    <img src="<?= htmlspecialchars($libro['portada']) ?>" alt="Portada de <?= htmlspecialchars($libro['titulo']) ?>">
                <?php endif; ?>
                <h3><?= htmlspecialchars($libro['titulo']) ?></h3>
            </a>
        <?php endwhile; ?>
    </div>

    <footer class="footer-sesion">
        <a href="biblioteca.php" class="boton-footer">Volver al inicio</a>
    </footer>
</body>
</html>

