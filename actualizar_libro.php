<?php
session_start();// Inicia la sesión para acceder a datos del usuario
require_once 'db.php';
// Verifica si el usuario está autenticado; si no, redirige al login
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

// Verifica si la solicitud fue realizada por el método POST
if ($_SERVER['REQUEST_METHOD'] === 'POST')
 {    
     $usuario_id = $_SESSION['usuario']['id'];
      // Recoge los datos enviados desde el formulario
    $id = $_POST['id'];
    $titulo = trim($_POST['titulo']);
    $autor = trim($_POST['autor']);
     $descripcion = trim($_POST['descripcion']);
    $nota = trim($_POST['nota']);
    $tipo = $_POST['tipo'];
    $estado = $_POST['estado'];
    
    if (empty($titulo) || empty($autor) || empty($tipo) || empty($estado)) {
        die("Faltan datos obligatorios como el título, autor, tipo o estado.");
    }
    $tipo_estanteria = $_POST['tipo_estanteria'];
    $estante_existente = $_POST['estante_existente'] ?? null;
    $nuevo_estante = trim($_POST['nuevo_estante']);
    $calificacion = null;
    $fecha_inicio = null;
    $fecha_finalizacion = null;
     // Solo si el estado es "leído" o "abandonado", se permiten fechas y calificación
    if ($estado === 'leido' || $estado === 'abandonado') {
        $calificacion = $_POST['calificacion'] ?? null;
        $fecha_inicio = $_POST['fecha_inicio'] ?? null;
        $fecha_finalizacion = $_POST['fecha_finalizacion'] ?? null;
    }
    
      // Manejo del estante
    $id_estante = null;
    if ($tipo_estanteria === 'estante') {
        if (!empty($nuevo_estante)) {
            $stmt_est = $conn->prepare("INSERT INTO estantes (nombre, usuario_id) VALUES (?, ?)");
            $stmt_est->bind_param("si", $nuevo_estante, $usuario_id);
            $stmt_est->execute();
            $id_estante = $stmt_est->insert_id;
            $stmt_est->close();
        } elseif (!empty($estante_existente)) {
            $id_estante = $estante_existente;
        }
    }
     
     $portada = trim($_POST['portada_actual']);// valor si no se realizan cambios
    
  // Si se elige subir una imagen desde archivo
if (!empty($_FILES['portada_archivo']['name'])) {
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
 elseif (!empty($_POST['portada_url'])) 
     {
    $url = trim($_POST['portada_url']);
    if (!empty($url)) 
        {
            $portada = $url;
        }
    }

   //si se usa una nueva portada se elimina la anterios (si se subio desde archivo)
    $stmt = $conn->prepare("SELECT portada FROM libros WHERE id = ? AND usuario_id = ?");
    $stmt->bind_param("ii", $id, $usuario_id);
    $stmt->execute();
    $stmt->bind_result($portada_anterior);
    $stmt->fetch();
    $stmt->close();
        if ($portada_anterior !== $portada && !empty($portada_anterior) && 
    !filter_var($portada_anterior, FILTER_VALIDATE_URL) && 
    $portada_anterior !== 'imagenes/iconos/icono_portada.avif') {
    
    if (file_exists($portada_anterior)) {
        unlink($portada_anterior);
    }
}
     // Validación de fechas
    if (($estado === 'leido' || $estado === 'abandonado') && !empty($fecha_inicio) && !empty($fecha_finalizacion)) {
        if ($fecha_finalizacion < $fecha_inicio) {
            die("La fecha de finalización no puede ser anterior a la de inicio.");
        }
    }

    // Prepara la consulta para actualizar los datos del libro en la base de datos
    $stmt = $conn->prepare("UPDATE libros SET titulo=?, autor=?, portada=?, fecha_inicio=?, fecha_finalizacion=?, descripcion=?, nota=?, calificacion=?, tipo=?, estado=?, estante_id=? WHERE id=?");
    $stmt->bind_param("ssssssssssii", $titulo, $autor,$portada, $fecha_inicio, $fecha_finalizacion, $descripcion, $nota, $calificacion, $tipo, $estado,$id_estante, $id);
   
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
