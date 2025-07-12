<?php
session_start(); // Inicia la sesión para acceder a los datos del usuario

// Verifica si el usuario ha iniciado sesión, si no, lo redirige al login
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

include 'db.php'; 

$usuario_id = $_SESSION['usuario']['id']; // Obtiene el ID del usuario desde la sesión

// Prepara la consulta para obtener todas las estanterías del usuario, 
$stmt = $conn->prepare("SELECT * FROM estantes WHERE usuario_id = ? ORDER BY id DESC");
$stmt->bind_param("i", $usuario_id); 
$stmt->execute(); // Ejecuta la consulta
$resultado = $stmt->get_result(); // Obtiene el resultado 
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Estanterías</title>
  <link rel="stylesheet" href="style.css"> 
</head>
<body>
  <div class="contenedor-estanterias">
  <h1>Mis Estanterías</h1> 
  
  <!-- Botón para ir al formulario de agregar nueva estantería -->
  <a href="agregar_estanteria.php" class="boton-agregar">Agregar nueva estantería</a>
  
  <!-- Si hay estanterías registradas, las muestra -->
  <?php if ($resultado->num_rows > 0): ?>
    <?php while($estante = $resultado->fetch_assoc()): ?> 
      <div class="estanteria">
        <!-- Enlace al detalle de la estantería -->
        <a href="ver_estanteria.php?id=<?= $estante['id'] ?>">
          <?= htmlspecialchars($estante['nombre']) ?> <!-- Muestra el nombre de la estantería -->
        </a>
      </div>
    <?php endwhile; ?>
  <?php else: ?>
    <!-- Mensaje si no hay estanterías creadas -->
    <p>No has creado ninguna estantería todavía.</p>
  <?php endif; ?>
  <p><a href="biblioteca.php">Volver al inicio</a></p>
</div>
</body>
</html>

