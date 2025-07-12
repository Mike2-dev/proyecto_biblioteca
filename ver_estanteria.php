<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}
include 'db.php';
// Obtiene el ID de la estantería desde la URL
$estante_id = $_GET['id'] ?? null;
if (!$estante_id) {
    echo "Estantería no especificada.";
    exit();
}
$usuario_id = $_SESSION['usuario']['id'];
// Verifica que la estantería exista y pertenezca al usuario
$stmt = $conn->prepare("SELECT nombre FROM estantes WHERE id = ? AND usuario_id = ?");
$stmt->bind_param("ii", $estante_id, $usuario_id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    echo "Estantería no encontrada.";
    exit();
}
$estanteria = $resultado->fetch_assoc();
//se obtienen los libros que pertenecen a esa estantería
$stmt_libros = $conn->prepare("SELECT id, titulo, portada FROM libros WHERE estante_id = ?");
$stmt_libros->bind_param("i", $estante_id);
$stmt_libros->execute();
$libros = $stmt_libros->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Libros en <?= htmlspecialchars($estanteria['nombre']) ?></title>
    <link rel="stylesheet" href="style.css">

</head>
<body class="estanteria">
<!-- Título con el nombre de la estantería -->
<h1 class="titulo-estanteria">Libros en: <?= htmlspecialchars($estanteria['nombre']) ?></h1>
 <a href="agregar_libro.php" class="boton-agregar"> Agregar libro</a>
<div class="contenedor-libros">
    <?php if ($libros->num_rows > 0): ?>
        <?php while ($libro = $libros->fetch_assoc()): ?>
            <div class="libro">
                <a href="detalles.php?id=<?= $libro['id'] ?>">
                <!-- Mostrar portada del libro si tiene, si no una imagen genérica -->
                    <?php if (!empty($libro['portada'])): ?>
                        <img src="<?= htmlspecialchars($libro['portada']) ?>" alt="Portada">
                    <?php else: ?>
                        <img src="imagenes/iconos/icono_portada.jpg" alt="Sin portada">
                    <?php endif; ?>
                    <span><?= htmlspecialchars($libro['titulo']) ?></span>
                </a>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
    <!-- Mensaje si no hay libros en la estantería -->
        <p class="mensaje-vacio">No hay libros en esta estantería.</p>
    <?php endif; ?>
</div>
<div class="acciones-estanteria">
  <p><a href="editar_estanteria.php?id=<?= $estante_id ?>">Editar nombre de estantería</a></p>
  <p><a href="eliminar_estanteria.php?id=<?= $estante_id ?>" onclick="return confirm('¿Seguro que deseas eliminar esta estantería?');">Eliminar estantería</a></p>
  <p><a href="estanteria.php">Volver a estanterías</a></p>
</div>
</body>
</html>
