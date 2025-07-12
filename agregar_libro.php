<?php
session_start(); // Inicia la sesión

// Verifica si el usuario está autenticado; si no, redirige al login
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

require_once 'db.php'; 
$usuario_id = $_SESSION['usuario']['id']; // Obtiene el ID del usuario de la sesión

// Prepara la consulta para obtener las estanterías del usuario actual, ordenadas alfabéticamente
$estantes = $conn->prepare("SELECT id, nombre FROM estantes WHERE usuario_id = ? ORDER BY nombre ASC");
$estantes->bind_param("i", $usuario_id);
$estantes->execute();
$resultado_estantes = $estantes->get_result(); // Ejecuta y guarda el resultado
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Agregar Libro</title>
   <link rel="stylesheet" href="style.css"> 
</head>
<body> 
<div class="agregar_libro">
  <h2>Agregar Libro</h2>

  <!-- Formulario para agregar un libro -->
  <form action="guardar_libro.php" method="POST" enctype="multipart/form-data">
    
    <label>Título: <input type="text" name="titulo" required></label>

    <label>Autor: <input type="text" name="autor" required></label>

    <!-- Seleccion del método para subir portada -->
    <label>Portada:</label>
    <select name="opcion_portada" id="opcion_portada" onchange="mostrarOpcionesPortada(this.value)">
        <option value="ninguna"> Selecciona una opción </option>
        <option value="archivo">Subir desde archivo</option>
        <option value="url">Ingresar URL</option>
    </select>

    <!-- Subida desde archivo -->
    <div id="portada-archivo">
        <label>Subir imagen desde tu dispositivo:</label>
        <input type="file" name="portada_archivo" accept="image/*">
    </div>

    <!-- Ingreso de URL -->
    <div id="portada-url" >
        <label>Ingresar URL de la imagen:</label>
        <input type="url" name="portada_url" placeholder="URL">
    </div>

    <label>Descripción: <textarea name="descripcion" rows="4"></textarea></label>

    <label>Nota personal: <textarea name="nota" rows="3"></textarea></label>

    <label>Tipo de libro:</label>
    <select name="tipo">
      <option value="fisico">Físico</option>
      <option value="digital">Digital</option>
      <option value="audiolibro">Audiolibro</option>
    </select>

    <!-- Estado del libro -->
    <label>Estado:</label>
    <select name="estado" id="estado" onchange="mostrarOpcionesEstado(this.value)">
      <option value="leido">Leído</option>
      <option value="pendiente">Pendiente</option>
      <option value="abandonado">Abandonado</option>
      <option value="deseo">Lista de deseos</option>
    </select>

    <!-- Campos que solo se muestran si el estado es leído o abandonado -->
    <div id="campos-lectura" >
        <label>Calificación (0 a 5):</label>
        <input type="number" name="calificacion" min="0" max="5" step="0.5">

        <label>Fecha de inicio:</label>
        <input type="date" id="fecha_inicio" name="fecha_inicio" onchange="document.getElementById('fecha_fin').min = this.value">

        <label>Fecha de finalización:</label>
        <input type="date" id="fecha_finalizacion" name="fecha_finalizacion">
    </div>

    <!-- Selección de estantería -->
    <label>Estanteria:</label>
    <select name="tipo_estanteria" id="tipo_estanteria" onchange="mostrarOpcionesEstante(this.value)">
      <option value="sin estante">Sin estante</option>
      <option value="estante">Estante</option>
    </select>

    <!-- Opciones para seleccionar o crear estantería -->
    <div id="estante-opciones" >
      <label>Selecciona estantería:</label>
      <select name="estante_existente">
        <option value="">-- Selecciona una --</option>
        <?php while ($row = $resultado_estantes->fetch_assoc()): ?>
        <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['nombre']) ?></option>
        <?php endwhile; ?>
      </select>

      <label>O crear nuevo estante:</label>
      <input type="text" name="nuevo_estante" placeholder="Nombre del nuevo estante">
    </div>

    <button type="submit">Guardar libro</button>
  </form>

  <!-- Scripts para mostrar/ocultar campos según  lo que seleccione el usuario -->
  <script>
    function mostrarOpcionesEstante(valor) {
      const opciones = document.getElementById('estante-opciones');
      opciones.style.display = (valor === 'estante') ? 'block' : 'none';
    }

    function mostrarOpcionesEstado(estado) {
      const camposLectura = document.getElementById('campos-lectura');
      if (estado === 'leido' || estado === 'abandonado') {
        camposLectura.style.display = 'block';
      } else {
        camposLectura.style.display = 'none';
      }
    }

    function mostrarOpcionesPortada(valor) {
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

    // Inicializa los campos visibles al cargar la página según los valores actuales
    document.addEventListener('DOMContentLoaded', function () {
      const estadoActual = document.getElementById('estado').value;
      mostrarOpcionesEstado(estadoActual);

      const tipoEstanteria = document.getElementById('tipo_estanteria').value;
      mostrarOpcionesEstante(tipoEstanteria);

      const opcionActual = document.getElementById('opcion_portada').value;
      mostrarOpcionesPortada(opcionActual);
    });
  </script>
</div>
</body> 
</html>
