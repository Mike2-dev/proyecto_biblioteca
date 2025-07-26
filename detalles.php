<?php
session_start(); 
$mensaje = '';
if (isset($_GET['actualizado']) && $_GET['actualizado'] == '1') {
    $mensaje = 'Libro actualizado correctamente.';
}
include 'db.php';
// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php"); 
    exit();
}
$id = $_GET['id'] ?? null; // Obtiene el ID del libro desde la 
$usuario = $_SESSION['usuario']; // Guarda los datos del usuario actual

// Si no se encontro una ID, muestra mensaje de error
if (!$id) {
    echo "ID de libro no especificado.";
    exit();
}
// se ejecuta una consulta para obtener el libro por su ID
$sql = "SELECT * FROM libros WHERE id = ? AND usuario_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $id, $usuario['id']);
$stmt->execute();
$result = $stmt->get_result();
// Si no se encuentra el libro muestra un mensaje
if ($result->num_rows !== 1) {
    echo "Libro no encontrado o acceso no permitido.";
    exit();
}
$libro = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($libro['titulo']) ?>-Detalles</title> 
    <link rel="stylesheet" href="style.css"> 
</head>

<body class="detalle-libro">
<div class="titulo">
    <h1>Detalles del libro</h1>
</div>

<div class="libro-detalle">
    <!-- Muestra la portada del libro -->
    <?php if (!empty($libro['portada'])): ?>
        <img src="<?= htmlspecialchars($libro['portada']) ?>" alt="Portada">
    <?php endif; ?>

    <!-- Detalles del libro -->
    <div class="info-libro">
        <?php if (!empty($mensaje)): ?>
            <div class="mensaje-exito"><?= $mensaje ?></div>
        <?php endif; ?>
        <h2><?= htmlspecialchars($libro['titulo']) ?></h2>
        <p><strong>Autor:</strong> <?= htmlspecialchars($libro['autor']) ?></p>

        <!-- Muestra fechas solo si el estado es leído o abandonado -->
        <?php if (!empty($libro['fecha_inicio']) && ($libro['estado'] === 'leido' || $libro['estado'] === 'abandonado')): ?>
            <p><strong>Fecha de inicio:</strong> <?= htmlspecialchars($libro['fecha_inicio']) ?></p>
        <?php endif; ?>

        <?php if (!empty($libro['fecha_finalizacion']) && ($libro['estado'] === 'leido' || $libro['estado'] === 'abandonado')): ?>
            <p><strong>Fecha de finalización:</strong> <?= htmlspecialchars($libro['fecha_finalizacion']) ?></p>
        <?php endif; ?> 

        <p><strong>Descripcion:</strong> <?= htmlspecialchars($libro['descripcion']) ?></p>
        <p><strong>Nota:</strong> <?= htmlspecialchars($libro['nota']) ?></p>
        
        <!-- Calificación solo para libros leídos o abandonados -->
        <?php if (!empty($libro['calificacion']) && ($libro['estado'] === 'leido' || $libro['estado'] === 'abandonado')): ?>
            <p><strong>Calificación:</strong> <?= htmlspecialchars($libro['calificacion']) ?>/5</p>
        <?php endif; ?>

        <!-- cambio de nombres para tipo y estado-->
        <?php
        $tipos = [
            'fisico' => 'Físico',
            'digital' => 'Digital',
            'audiolibro' => 'Audiolibro'
        ];
        $estados = [
            'leido' => 'Leído',
            'pendiente' => 'Pendiente',
            'abandonado' => 'Abandonado',
            'deseo' => 'Lista de deseos'
        ];
        ?>
        <p><strong>Tipo de libro:</strong> <?= $tipos[$libro['tipo']] ?? 'Desconocido' ?></p>
        <p><strong>Estado:</strong> <?= $estados[$libro['estado']] ?? 'Desconocido' ?></p>
    </div>
</div>

<!-- Botones -->
<footer class="footer-sesion">
    <a href="editar.php?id=<?= $libro['id'] ?>" class="boton-footer">Editar libro</a>
    <a href="eliminar_libro.php?id=<?= $libro['id'] ?>" onclick="return confirm('¿Estás seguro de que deseas eliminar este libro?');" class="boton-footer">Eliminar libro</a>
    <a href="biblioteca.php" class="boton-footer">Volver al inicio</a>
</footer>
</body>
</html>
