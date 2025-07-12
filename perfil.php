<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
} 

include 'db.php';
// Se obtiene el ID del usuario desde la sesión
$id = $_SESSION['usuario']['id'];
// Consulta los datos del usuario actual
$sql = "SELECT * FROM usuarios WHERE id =?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i",$id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
if (!$user) {
    echo "No se encontró el usuario.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Perfil de Usuario</title>
  <link rel="stylesheet" href="style.css">
</head>
<body class="perfil-pagina">

  <div class="contenedor-perfil">
    <form action="actualizar_perfil.php" method="POST" enctype="multipart/form-data">
        <h3>Modificar perfil</h3>
        <h2> <?php echo "Hola ".htmlspecialchars($user['usuario']); ?></h2>
        <!-- Muestra la foto de perfil actual (o imagen predeterminada si no hay ninguna) -->
        <input type="hidden" name="perfil_actual" value="<?= htmlspecialchars($user['foto_perfil']) ?>">
        <input type="hidden" name="id" value="<?= htmlspecialchars($user['id']) ?>">

        <div>
            <label>Foto de perfil</label>
            <?php if (!empty($user['foto_perfil'])): ?>
                <img src="<?= htmlspecialchars($user['foto_perfil']) ?>" alt="Foto de perfil" style="width:100px; height:100px; border-radius:50%;">
            <?php else: ?>
                 <img src="imagenes/iconos/icono_perfil.webp" alt="Perfil predeterminado" class="img-perfil">
            <?php endif; ?>
        </div>
        <label >Nombre de usuario:</label>
        
        <p><?= htmlspecialchars($user['usuario']) ?></p> 
        <input type="hidden" name="usuario" value="<?= htmlspecialchars($user['usuario']) ?>">
        
        <label>Nuevo nombre de usuario:</label>
        <input type="text" name="usuario" value="<?= htmlspecialchars($user['usuario']) ?>" required>

        <label>Correo electrónico:</label>
        <p><?= htmlspecialchars($user['correo']) ?></p> 
        <input type="hidden" name="correo" value="<?= htmlspecialchars($user['correo']) ?>">
        <!-- Opciones para cambiar la foto de perfil (archivo o URL) -->
        <label>Cambiar Foto de perfil:</label>
        <select name="opcion_fotoperfil" id="opcion_fotoperfil" onchange="mostrarOpcionesFotoPerfil(this.value)">
            <option value="ninguna">-- Selecciona una opción --</option>
            <option value="archivo">Subir desde archivo</option>
            <option value="url">Ingresar URL</option>
        </select>

        <div id="portada-archivo" class="foto-perfil-archivo">
            <label>Subir imagen desde tu dispositivo:</label>
            <input type="file" name="portada_archivo" accept="image/*">
        </div>

        <div id="portada-url" class="foto-perfil-url"">
            <label>Ingresar URL de la imagen:</label>
            <input type="url" name="portada_url" placeholder="URL">
        </div>

        <input type="submit" value="Actualizar perfil">
    </form>

    <form action="eliminar_cuenta.php" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar tu cuenta? Esta acción no se puede deshacer.')">
        <input type="submit" value="Eliminar cuenta" class="boton-eliminar">
    </form>

    <form action="logout.php" method="POST">
        <input type="submit" value="Cerrar sesión">
    </form>
  </div>

  <script>
  // oculta o muestra los campos de archivo o URL dependiendo de la selección
    function mostrarOpcionesFotoPerfil(valor) {
        const divArchivo = document.getElementById('portada-archivo');
        const divURL = document.getElementById('portada-url');

        if (valor === 'archivo') {
            divArchivo.style.display = 'block';
            divURL.style.display = 'none';
        } else if (valor === 'url') {
            divArchivo.style.display = 'none';
            divURL.style.display = 'block';
        } else {
            divArchivo.style.display = 'none';
            divURL.style.display = 'none';
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const opcionActual = document.getElementById('opcion_fotoperfil').value;
        mostrarOpcionesFotoPerfil(opcionActual);
    });
  </script>
</body>
</html>
