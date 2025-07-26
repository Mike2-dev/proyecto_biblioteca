<?php
session_start(); // Inicia la sesión para mantener al usuario autenticado
include 'db.php'; 
// Verifica si el usuario ha iniciado sesión; si no, lo redirige al login
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

$id = $_SESSION['usuario']['id']; // Obtiene el ID del usuario desde la sesión

// Consulta SQL para obtener la foto de perfil del usuario actual
$sql = "SELECT foto_perfil FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$resultado = $stmt->get_result();
$user = $resultado->fetch_assoc(); 
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
  <title>Biblioteca</title>
  <link rel="stylesheet" href="style.css"> 
</head>
<body class="pagina-inicio">

  <header class="encabezado">
    <div class="perfil">
      <a href="perfil.php" class="btn-perfil" title="Ver perfil">
        <?php if (!empty($user['foto_perfil'])): ?>
          <!-- Muestra la foto de perfil del usuario si existe -->
          <img src="<?= htmlspecialchars($user['foto_perfil']) ?>" alt="Perfil" class="img-perfil">
        <?php else: ?>
          <!-- Muestra imagen predeterminada si no hay foto aun -->
          <img src="imagenes/iconos/icono_perfil.webp" alt="Perfil predeterminado" class="img-perfil">
        <?php endif; ?>
      </a>
    </div>
<div class="titulo-contenedor"> 
    <h1 class="titulo-principal">BIBLIOTECAA</h1>
</div>
    <!-- Barra de búsqueda   -->
    <form action="busqueda.php" method="GET" class="barra-busqueda">
      <input type="text" name="busqueda" placeholder="Buscar autor o titulo del libro...">
        <button type="submit">Buscar</button>
    </form>
  </header>

  <!-- Menú principal con accesos a diferentes secciones -->
  <main class="menu-opciones">

    <!-- Enlace a la estantería del usuario -->
    <a href="estanteria.php" class="opcion">  
      <span>Estantería</span>
      <img src="imagenes/iconos/icono_estanterias.jpg" alt="Crear estantería">
    </a>

    <!-- Enlace para agregar un nuevo libro -->
    <a href="agregar_libro.php" class="opcion">
      <span>Agregar libro</span>
      <img src="imagenes/iconos/icono_agregar.avif" alt="Agregar libro">
    </a>

    <!-- Enlace a los libros leídos -->
    <a href="leidos.php" class="opcion">
      <span>Libros leídos</span>
      <img src="imagenes/iconos/icono_leidos.webp" alt="Leídos">
    </a>

    <!-- Enlace a libros pendientes -->
    <a href="pendientes.php" class="opcion">
      <span>Libros pendientes</span>
      <img src="imagenes/iconos/icono_pendientes.webp" alt="Pendientes">
    </a>

    <!-- Enlace a libros abandonados -->
    <a href="abandonados.php" class="opcion">
      <span>Libros abandonados</span>
      <img src="imagenes/iconos/icono_abandonados.jpg" alt="Abandonados">
    </a>

    <!-- Enlace a la lista de deseos -->
    <a href="deseos.php" class="opcion">
      <span>Lista de deseos</span>
      <img src="imagenes/iconos/icono_deseos.jpg" alt="Lista de deseos">
    </a>

  </main>  
</body>
</html>
