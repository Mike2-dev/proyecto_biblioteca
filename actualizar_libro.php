<?php
session_start();// Inicia la sesión para acceder a datos del usuario

// Verifica si el usuario está autenticado; si no, redirige al login
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

require_once 'db.php';
// Verifica si la solicitud fue realizada por el método POST
if ($_SERVER['REQUEST_METHOD'] === 'POST')
 {
      // Recoge los datos enviados desde el formulario
    $id = $_POST['id'];
    $titulo = trim($_POST['titulo']);
    $autor = trim($_POST['autor']);
    $opcion_portada = $_POST['opcion_portada'] ?? 'ninguna';// Puede ser 'archivo', 'url' o 'ninguna'
    $portada = trim($_POST['portada_actual']);// valor si no se realizan cambios

  // Si se elige subir una imagen desde archivo
if ($opcion_portada === 'archivo' && !empty($_FILES['portada_archivo']['name'])) {
    $nombre_archivo = uniqid() . "_" . basename($_FILES["portada_archivo"]["name"]);
    $ruta_destino = "imagenes/portadas/" . $nombre_archivo;

    if (!is_dir("imagenes/portadas/")) {
        mkdir("imagenes/portadas/", 0755, true);
    }
    // Mueve el archivo subido a la carpeta destino y actualiza la ruta de la portada
    if (move_uploaded_file($_FILES["portada_archivo"]["tmp_name"], $ruta_destino)) {
        $portada = $ruta_destino;
    }
} // Si se elige usar una URL como portada
 elseif ($opcion_portada === 'url') 
     {
    $url = trim($_POST['portada_url']);
    if (!empty($url)) 
        {
            $portada = $url;
        }
    }

    $descripcion = trim($_POST['descripcion']);
    $nota = trim($_POST['nota']);
    $tipo = $_POST['tipo'];
    $estado = $_POST['estado'];

    $fecha_inicio = null;
    $fecha_finalizacion = null;
    $calificacion = null;
    // Solo si el estado es "leído" o "abandonado", se permiten fechas y calificación
    if ($estado === 'leido' || $estado === 'abandonado') {
        $calificacion = $_POST['calificacion'] ?? null;
        $fecha_inicio = $_POST['fecha_inicio'] ?? null;
        $fecha_finalizacion = $_POST['fecha_finalizacion'] ?? null;
    }
    // Prepara la consulta para actualizar los datos del libro en la base de datos
    $stmt = $conn->prepare("UPDATE libros SET titulo=?, autor=?, portada=?, fecha_inicio=?, fecha_finalizacion=?, descripcion=?, nota=?, calificacion=?, tipo=?, estado=? WHERE id=?");
    $stmt->bind_param("ssssssssssi", $titulo, $autor,$portada, $fecha_inicio, $fecha_finalizacion, $descripcion, $nota, $calificacion, $tipo, $estado, $id);
    // Validación: la fecha final no puede ser anterior a la fecha de inicio
 if ($estado === 'leido' || $estado === 'abandonado') {
   
    if (!empty($fecha_inicio) && !empty($fecha_finalizacion) && $fecha_finalizacion < $fecha_inicio) {
        die("La fecha de finalización no puede ser anterior a la de inicio.");
    }
}// Ejecuta la actualización y redirige si tiene éxito
    if ($stmt->execute()) {
        header("Location: detalles.php?id=$id&actualizado=1");
        exit();
    } else {
        // se muestra si hay un error 
        echo "Error al actualizar: " . $conn->error;
    }
}
?>
