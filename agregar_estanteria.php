<?php
session_start(); // Inicia la sesión para acceder a los datos del usuario

// Verifica si el usuario está autenticado; si no, redirige al login
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

include 'db.php'; 

$usuario_id = $_SESSION['usuario']['usuario_id'] ?? 1; // id de usuario

// Verifica si el formulario fue enviado por método POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre_estanteria']); // Obtiene el nombre de la estanteria

    // Verifica que el nombre no esté vacío
    if (!empty($nombre)) {
        // Prepara la consulta para insertar una nueva estantería en la base de datos
        $stmt = $conn->prepare("INSERT INTO estantes (nombre, usuario_id) VALUES (?, ?)");
        $stmt->bind_param("si", $nombre, $usuario_id); 

        // Ejecuta la consulta y redirige 
        if ($stmt->execute()) {
            header("Location: estanteria.php"); 
            exit();
        } else {
            $error = "Error al guardar la estantería."; // Mensaje si hay un error al agregar
        }
    } else {
        $error = "El nombre no puede estar vacío.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Agregar estantería</title>
  <link rel="stylesheet" href="style.css"> 
</head>
<body>
<div class="formulario-estanteria">
  <h2>Agregar nueva estantería</h2>

  <!-- Formulario para crear una nueva estantería -->
  <form method="POST">
    
    <input type="text" name="nombre_estanteria" placeholder="Nombre de la estantería" required>
    <input type="submit" value="Crear estantería">

    <!-- Si existe un error, se muestra aquí -->
    <?php if (!empty($error)): ?>
      <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
  </form>
  <p><a href="estanteria.php">Volver a estantería</a></p>
</div>

</body>
</html>
