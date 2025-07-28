<?php
session_start(); // Inicia la sesión
include 'db.php'; 
// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php"); 
    exit();
}

$id = $_GET['id'] ?? null; // se obtiene el ID del libro desde la URL, o null
// Si no se pasó un ID, muestra un mensaje y se detiene la ejecución
if (!$id) {
    echo "ID de libro no especificado.";
    exit();
}
$usuario_id = $_SESSION['usuario']['id'];
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
// obtiene los estantes del usuario
$estantes = $conn->prepare("SELECT id, nombre FROM estantes WHERE usuario_id = ? ORDER BY nombre ASC");
$estantes->bind_param("i", $usuario_id);
$estantes->execute();
$resultado_estantes = $estantes->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
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
    <label>Cambiar portada:</label>
   <select name="opcion_portada" id="opcion_portada" onchange="mostrarOpcionesPortada(this.value)">
    <option value="ninguna">-- Selecciona una opción --</option>
    <option value="archivo">Subir desde archivo</option>
    <option value="url">Ingresar URL</option>
</select>
    <!-- Campo para subir imagen desde dispositivo -->
    <div id="portada-archivo" style="display: none;">
        <label>Subir imagen:</label>
        <input type="file" name="portada_archivo" accept="image/*" disabled>
    </div>

     <!-- Campo para ingresar URL de portada -->
    <div id="portada-url" style="display: none;">
        <label>Ingresar URL de la imagen:</label>
        <input type="URL" name="portada_url" id="portada_url" disabled>
    </div>
    <input type="hidden" name="portada_actual" value="<?= htmlspecialchars($libro['portada']) ?>">
   
    <label>Sinopsis:</label>
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
        <input type="date" id="fecha_inicio" name="fecha_inicio" value="<?= htmlspecialchars($libro['fecha_inicio']) ?>" onchange="document.getElementById('fecha_finalizacion').min = this.value">

        <label>Fecha de finalización:</label>
        <input type="date" id="fecha_finalizacion" name="fecha_finalizacion" value="<?= htmlspecialchars($libro['fecha_finalizacion']) ?>">

        <label>Calificación (0 a 5):</label>
        <input type="number" name="calificacion" min="0" max="5" step="0.5" value="<?= htmlspecialchars($libro['calificacion']) ?>">
    </div>

    <label>Estantería:</label>
    <select name="tipo_estanteria" id="tipo_estanteria" onchange="mostrarOpcionesEstante(this.value)">
        <option value="sin estante" <?= empty($libro['estante_id']) ? 'selected' : '' ?>>Sin estante</option>
        <option value="estante" <?= !empty($libro['estante_id']) ? 'selected' : '' ?>>Estante</option>
    </select>

    <div id="estante-opciones" >
        <label>Selecciona estantería:</label>
        <select name="estante_existente">
            <option value="">-- Selecciona una --</option>
            <?php while ($row = $resultado_estantes->fetch_assoc()): ?>
            <option value="<?= $row['id'] ?>" <?= $libro['estante_id'] == $row['id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($row['nombre']) ?>
            </option>
            <?php endwhile; ?>
        </select>

        <label>O crear nuevo estante:</label>
        <input type="text" name="nuevo_estante" placeholder="Nombre del nuevo estante">
    </div>

    <input type="submit" value="Actualizar libro">
</form>

<script>
    // Muestra u oculta campos según la opción de estantes
     function mostrarOpcionesEstante(valor) {
        const opciones = document.getElementById('estante-opciones');
        opciones.style.display = (valor === 'estante') ? 'block' : 'none';
    }
    // Muestra u oculta campos según la opción de portada 
       function mostrarOpcionesPortada(valor) {
        const divArchivo = document.getElementById('portada-archivo');
        const inputArchivo = document.getElementById('portada_archivo');
        const divURL = document.getElementById('portada-url');
        const inputURL = document.getElementById('portada_url');

        if (valor === 'archivo') {
            divArchivo.style.display = 'block';
            inputArchivo.disabled = false;

            divURL.style.display = 'none';
            inputURL.disabled = true;
        } else if (valor === 'url') {
            divArchivo.style.display = 'none';
            inputArchivo.disabled = true;

            divURL.style.display = 'block';
            inputURL.disabled = false;
        } else {
            divArchivo.style.display = 'none';
            inputArchivo.disabled = true;

            divURL.style.display = 'none';
            inputURL.disabled = true;
        }
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
        if (portadaActual.startsWith('http')) {
            select.value = 'url';
        } else if (portadaActual.length > 0) {
            select.value = 'archivo';
        } else {
            select.value = 'ninguna';
        }
        mostrarOpcionesPortada(select.value);
        //muestra las estanterias 
        const tipoEstanteria = document.getElementById('tipo_estanteria').value;
        mostrarOpcionesEstante(tipoEstanteria);
    });
</script>
</div>
</html>
