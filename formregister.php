<!DOCTYPE html>
<html lang="es"> 
<head>
  <meta charset="UTF-8"> 
  <title>Registrarse</title> 
  <link rel="stylesheet" href="style.css"> 
</head>
<body class="login"> 
  <div class="login-box"> 
    <h2>Registro</h2> 
    <!-- Formulario de registro que envía datos a 'registerdb.php' usando el método POST -->
    <form action="registerdb.php" method="POST">
        <!-- Muestra un mensaje de error si el correo ya está registrado, usando un parámetro GET en la URL -->
        <?php if (isset($_GET['error']) && $_GET['error'] === 'correo_existente'): ?>
            <div class="alerta-form">
                El correo de usuario ya está registrado.
            </div>
        <?php endif; ?>
      <input type="email" name="correo" placeholder="Correo electrónico" required>
      <input type="text" name="usuario" placeholder="Nombre de usuario" required>
      <input type="password" name="clave" placeholder="Contraseña" required>
      <input type="hidden" name="action" value="add">
      <input type="submit" value="Entrar">
    </form>
    <!-- Enlace para volver a la página de inicio de sesión -->
    <a href="login.php">Iniciar sesión</a>
  </div>
</body>
</html>