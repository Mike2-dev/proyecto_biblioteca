<?php
session_start(); // Inicia la sesión

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php"); 
    exit();
}

include 'db.php'; 

$id = $_GET['id'] ?? null; // se obtiene el ID del libro desde la URL, o null

// Si no se pasó un ID, muestra un mensaje y se detiene la ejecución
if (!$id) {
    echo "ID de libro no especificado.";
    exit();
}

// Consulta SQL para obtener los datos del libro 
$sql = "SELECT * FROM libros WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

// Si no se encuentra el libro, muestra un error
if ($result->num_rows !== 1) {
    echo "Libro no encontrado.";
    exit();
}
$libro = $result->fetch_assoc(); // Obtiene los datos del libro como array asociativo
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Libro</title>
  <link rel="stylesheet" href="style.css"> <!-- Enlace al archivo de estilos -->
</head>

<!-- Contenedor principal del formulario -->
<div class="agregar_libro">
<h2>Editar Libro</h2>

<!-- Formulario que envía los datos a actualizar_libro.php -->
<form action="actualizar_libro.php" method="POST" enctype="multipart/form-data">
    <!-- Campo oculto con el ID del libro -->
    <input type="hidden" name="id" value="<?= $libro['id'] ?>">

    <!-- Campo de título con valor actual del libro -->
    <label>Título:</label>
    <input type="text" name="titulo" value="<?= htmlspecialchars($libro['titulo']) ?>" required>
    <label>Autor:</label>
    <input type="text" name="autor" value="<?= htmlspecialchars($libro['autor']) ?>" required>
    <!-- Manejo de portada -->
    <label>Portada:</label>
    <!-- Se guarda la portada actual en un campo oculto -->
    <input type="hidden" name="portada_actual" value="<?= htmlspecialchars($libro['portada']) ?>">
    <!-- Opciones para elegir tipo de portada -->
    <select name="opcion_portada" id="opcion_portada" onchange="mostrarOpcionesPortada(this.value)">
        <option value="ninguna">-- Selecciona una opción --</option>
        <option value="archivo">Subir desde archivo</option>
        <option value="url">Ingresar URL</option>
    </select>

    <!-- Campo para subir imagen desde dispositivo -->
    <div id="portada-archivo">
        <label>Subir imagen desde tu dispositivo:</label>
        <input type="file" name="portada_archivo" accept="image/*">
    </div>

    <!-- Campo para ingresar URL de portada -->
    <div id="portada-url">
        <label>Ingresar URL de la imagen:</label>
        <input type="text" name="portada_url" value="<?= htmlspecialchars($libro['portada']) ?>">
    </div>
    <label>Descripción:</label>
    <textarea name="descripcion" rows="4"><?= htmlspecialchars($libro['descripcion']) ?></textarea>
    <label>Nota:</label>
    <textarea name="nota" rows="3"><?= htmlspecialchars($libro['nota'])?></textarea>

    <!-- Selección de tipo de libro -->
    <label>Tipo:</label>
    <select name="tipo">
        <option value="fisico" <?= $libro['tipo'] == 'fisico' ? 'selected' : '' ?>>Físico</option>
        <option value="digital" <?= $libro['tipo'] == 'digital' ? 'selected' : '' ?>>Digital</option>
        <option value="audiolibro" <?= $libro['tipo'] == 'audiolibro' ? 'selected' : '' ?>>Audiolibro</option>
    </select>

    <!-- Selección del estado del libro -->
    <label>Estado:</label>
    <select name="estado" id="estado" onchange="mostrarOpcionesEstado(this.value)">
        <option value="leido" <?= $libro['estado'] == 'leido' ? 'selected' : '' ?>>Leído</option>
        <option value="pendiente" <?= $libro['estado'] == 'pendiente' ? 'selected' : '' ?>>Pendiente</option>
        <option value="abandonado" <?= $libro['estado'] == 'abandonado' ? 'selected' : '' ?>>Abandonado</option>
        <option value="deseo" <?= $libro['estado'] == 'deseo' ? 'selected' : '' ?>>Lista de deseos</option>
    </select>

    <!-- solo se muestran si el estado es leído o abandonado -->
    <div id="campos-lectura">
        <label>Fecha de inicio:</label>
        <input type="date" id="fecha_inicio" name="fecha_inicio" value="<?= htmlspecialchars($libro['fecha_inicio']) ?>" onchange="document.getElementById('fecha_fin').min = this.value">

        <label>Fecha de finalización:</label>
        <input type="date" id="fecha_finalizacion" name="fecha_finalizacion" value="<?= htmlspecialchars($libro['fecha_finalizacion']) ?>">

        <label>Calificación (0 a 5):</label>
        <input type="number" name="calificacion" min="0" max="5" step="0.5" value="<?= htmlspecialchars($libro['calificacion']) ?>">
    </div>
    <input type="submit" value="Actualizar libro">
</form>

<script>
    // Muestra u oculta campos según la opción de portada 
    function mostrarOpcionesPortada(valor) {
        const divArchivo = document.getElementById('portada-archivo');
        const divURL = document.getElementById('portada-url');

        divArchivo.style.display = (valor === 'archivo') ? 'block' : 'none';
        divURL.style.display = (valor === 'url') ? 'block' : 'none';
    }

    // Muestra los campos solo si el estado es "leído" o "abandonado"
    function mostrarOpcionesEstado(estado) {
        const campos = document.getElementById('campos-lectura');
        if (estado === 'leido' || estado === 'abandonado') {
            campos.style.display = 'block';
        } else {
            campos.style.display = 'none';
        }
    }

    // Ejecuta al cargar la página
    document.addEventListener('DOMContentLoaded', function () {
        // Ajusta si los campos se ven o no según el estado actual
        const estadoActual = document.getElementById('estado').value;
        mostrarOpcionesEstado(estadoActual);

        // muestra si la portada actual es una URL o archivo dependiendo a la seleccion del usuario
        const portadaActual = "<?= $libro['portada'] ?>";
        if (portadaActual.startsWith("http")) {
            document.getElementById('opcion_portada').value = "url";
        } else if (portadaActual.length > 0) {
            document.getElementById('opcion_portada').value = "archivo";
        } else {
            document.getElementById('opcion_portada').value = "ninguna";
        }

        mostrarOpcionesPortada(document.getElementById('opcion_portada').value);
    });
</script>
</div>
</html>
