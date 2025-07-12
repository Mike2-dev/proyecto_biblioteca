<!DOCTYPE html> 
<html lang="es"> 
<head>
  <meta charset="UTF-8"> 
  <title>Iniciar sesión</title> 
  <link rel="stylesheet" href="style.css"> 
</head>
<body class="login"> 

  <div class="login-box"> 
    <h2>Iniciar sesión</h2> 

    <!-- Formulario que envía los datos a logindb.php usando el método POST -->
    <form action="logindb.php" method="POST">
      <!-- Bloque PHP que muestra mensajes de error si hay parámetros 'error' en la URL -->
      <?php if (isset($_GET['error']) && $_GET['error'] === 'clave_incorrecta'): ?>
        <div class="alerta-form">
          La contraseña es incorrecta. 
        </div>
      <?php elseif (isset($_GET['error']) && $_GET['error'] === 'usuario_no_encontrado'): ?>
        <div class="alerta-form">
          El usuario no está registrado. 
        </div>
      <?php endif; ?>

      <input type="text" name="correo" placeholder="Correo" required>
      <input type="password" name="clave" placeholder="Contraseña" required>
      <input type="submit" value="Entrar">
    </form>

    <!-- Enlace a la página de registro si el usuario no tiene cuenta -->
    <a href="formregister.php">¿No tienes cuenta? Regístrate</a>
  </div>

</body>
</html>
