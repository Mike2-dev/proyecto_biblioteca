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
    <h1 class="titulo-principal">Biblioteca</h1>
    <form class="barra-busqueda">
      <input type="text" placeholder="Buscar categoría o libro...">
    </form>
  </header>

  <main class="menu-opciones">
  <!-- Los enlaces llaman a mostrarModal(), impidiendo el acceso sin sesión -->
    <a href="#" onclick="mostrarModal()" class="opcion">
      <span>Estantería</span>
      <img src="imagenes/iconos/icono_estanterias.jpg" alt="Crear estantería">
    </a>
    <a href="#" onclick="mostrarModal()" class="opcion">
      <span>Agregar libro</span>
      <img src="imagenes/iconos/icono_agregar.avif" alt="Agregar libro">
    </a>
    <a href="#" onclick="mostrarModal()" class="opcion">
      <span>Libros leídos</span>
      <img src="imagenes/iconos/icono_leidos.webp" alt="Leídos">
    </a>
    <a href="#" onclick="mostrarModal()" class="opcion">
      <span>Libros pendientes</span>
      <img src="imagenes/iconos/icono_pendientes.webp" alt="Pendientes">
    </a>
<a href="#" onclick="mostrarModal()" class="opcion">
      <span>Libros abandonados</span>
      <img src="imagenes/iconos/icono_abandonados.jpg" alt="Abandonados">
    </a>
<a href="#" onclick="mostrarModal()" class="opcion">
      <span>Lista de deseos</span>
      <img src="imagenes/iconos/icono_deseos.jpg" alt="Lista de deseos">
    </a>
  </main>

 <footer class="footer-sesion">
   <a href="login.php" class="boton-footer">Iniciar sesión</a>
  <a href="formregister.php" class="boton-footer">Registrarse</a>
</footer>


  <div id="modal" class="modal">
    <div class="modal-contenido">
      <span class="cerrar" onclick="cerrarModal()">&times;</span>
      <p>Para agregar contenido es necesario registrarte o iniciar sesión.</p>
    </div>
  </div>

  <script>
  //para mostrar y ocultar el modal
    function mostrarModal() {
      document.getElementById('modal').style.display = 'block';
    }
    function cerrarModal() {
      document.getElementById('modal').style.display = 'none';
    }
  </script>

</body>
</html>