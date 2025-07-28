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
    <form action="registerdb.php" method="POST" onsubmit="return validarFormulario()">
        <!-- Muestra un mensaje de error si el correo ya está registrado, usando un parámetro GET en la URL -->
        <?php if (isset($_GET['error']) && $_GET['error'] === 'correo_existente'): ?>
            <div class="alerta-form">
                El correo de usuario ya está registrado.
            </div>
        <?php endif; ?>

      <?php if (isset($_GET['error']) && $_GET['error'] === 'clave_invalida'): ?>
  <div class="alerta-form">
    La contraseña no cumple con los requisitos.
  </div>
<?php endif; ?>
      
      <input type="email" name="correo" placeholder="Correo electrónico" required>
      <input type="text" name="usuario" placeholder="Nombre de usuario" required>
      <input type="password" name="clave" id="clave" placeholder="Contraseña" required>
      <!-- Mensaje dinámico que aparece SOLO si hay error -->
      <div id="errorClave"></div>
        <!-- Mensaje debajo del campo de contraseña -->
            <div id="mensajeClave">
                La contraseña debe tener al menos 8 caracteres, incluyendo una mayúscula, una minúscula y un número.
            </div>
      <input type="hidden" name="action" value="add">
      <input type="submit" value="Entrar">
    </form>
    <!-- Enlace para volver a la página de inicio de sesión -->
    <a href="login.php">Iniciar sesión</a>
  </div>
  <script>
    function validarFormulario() {
      const clave = document.getElementById("clave").value;
      const mensaje = document.getElementById("errorClave");
      const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,}$/;

      if (!regex.test(clave)) {
         mensaje.textContent = "Error: La contraseña no cumple con los requisitos.";
        mensaje.style.display = "block";
        return false; // detiene el envío del formulario
      } else {
          mensaje.textContent = "";
        mensaje.style.display = "none";
        return true; // permite el envío
      }
    }
  </script>
</body>
</html>
