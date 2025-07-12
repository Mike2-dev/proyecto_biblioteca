<?php
session_start();
//Inicia sesión y verifica que el usuario esté autenticado.
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

require_once 'db.php';
//ejecuta el código si la solicitud vino de un formulario enviado por POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario_id = $_SESSION['usuario']['id'];  //Se obtiene el ID del usuario actual desde la sesión
    //Se recopilan todos los datos del formulario:
    $titulo = trim($_POST['titulo']);
    $autor = trim($_POST['autor']);
    $descripcion = trim($_POST['descripcion']);
    $nota = trim($_POST['nota']);
    $tipo = $_POST['tipo'];
    $estado = $_POST['estado'];
    $tipo_estanteria = $_POST['tipo_estanteria'];
    $estante_existente = $_POST['estante_existente'] ?? null;
    $nuevo_estante = trim($_POST['nuevo_estante']);

    $calificacion = null;
    $fecha_inicio = null;
    $fecha_finalizacion = null;

    if ($estado === 'leido' || $estado === 'abandonado') {
        $calificacion = $_POST['calificacion'] ?? null;
        $fecha_inicio = $_POST['fecha_inicio'] ?? null;
        $fecha_finalizacion = $_POST['fecha_finalizacion'] ?? null;
    }
//Manejo de la portada
  $opcion_portada = $_POST['opcion_portada'] ?? 'ninguna';
    $portada_ruta = "imagenes/iconos/icono_portada.avif";

    if ($opcion_portada === 'archivo' && !empty($_FILES['portada_archivo']['name'])) {
        $nombre_archivo = uniqid() . "_" . basename($_FILES["portada_archivo"]["name"]);
        $ruta_destino = "imagenes/portadas" . $nombre_archivo;

        if (!is_dir("imagenes/portadas")) {
            mkdir("imagenes/portadas", 0755, true);
        }

        if (move_uploaded_file($_FILES["portada_archivo"]["tmp_name"], $ruta_destino)) {
            $portada_ruta = $ruta_destino;
        }
    } elseif ($opcion_portada === 'url' && !empty(trim($_POST['portada_url']))) {
        $portada_ruta = trim($_POST['portada_url']);
    }

    $id_estante = null;
    if ($tipo_estanteria === 'estante') {
        if (!empty($nuevo_estante)) {
            //Si se crea una nueva estantería, se inserta en la tabla estantes
            $stmt_est = $conn->prepare("INSERT INTO estantes (nombre, usuario_id) VALUES (?, ?)");
            $stmt_est->bind_param("si", $nuevo_estante, $usuario_id);
            $stmt_est->execute();
            $id_estante = $stmt_est->insert_id;
            $stmt_est->close();
        } elseif (!empty($estante_existente)) {
            $id_estante = $estante_existente;
        }
    }
    //Se asegura que la fecha de finalizacion no sea antes que la de inicio
    if ($estado === 'leido' || $estado === 'abandonado') {
    if (!empty($fecha_inicio) && !empty($fecha_finalizacion) && $fecha_finalizacion < $fecha_inicio) {
        die("La fecha de finalización no puede ser anterior a la de inicio.");
    }
}
    //insercion en la base de datos
    $stmt = $conn->prepare("INSERT INTO libros 
        (titulo, autor, portada, descripcion, nota, calificacion, fecha_inicio, fecha_finalizacion, tipo, estado, estante_id, usuario_id)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->bind_param("ssssssssssii",
        $titulo, $autor, $portada_ruta, $descripcion, $nota, $calificacion, $fecha_inicio, $fecha_finalizacion, $tipo, $estado, $id_estante, $usuario_id
    );

    if ($stmt->execute()) {
        header("Location: biblioteca.php?mensaje=libro_agregado");
        exit();
    } else {
        echo "Error al guardar el libro: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
