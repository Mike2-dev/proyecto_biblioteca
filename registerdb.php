<?php
session_start();
require_once 'db.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $correo = trim($_POST['correo']);
    $usuario = trim($_POST['usuario']);
    $clave = $_POST['clave'];
         // Validación de contraseña en el servidor
    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{8,}$/', $clave)) {
    header("Location: formregister.php?error=clave_invalida");
    exit();
}
    //Se consulta si el correo ya está registrado para evitar duplicados
    $consulta = $conn->prepare("SELECT id FROM usuarios WHERE correo = ?");
    $consulta->bind_param("s", $correo);
    $consulta->execute();
    $consulta->store_result();
    //Si ya existe, se redirige al formulario de registro con un mensaje de error
    if ($consulta->num_rows > 0) {
        header("Location: formregister.php?error=correo_existente");
        exit();
    }
    //se encripta la contraseña
    $claveHash = password_hash($clave, PASSWORD_DEFAULT);

    $foto_perfil = "imagenes/iconos/icono_perfil.webp";
    $inserta = $conn->prepare("INSERT INTO usuarios (correo, usuario, clave, foto_perfil) VALUES (?, ?, ?, ?)");
    $inserta->bind_param("ssss", $correo, $usuario, $claveHash, $foto_perfil);

    if ($inserta->execute()) {
    //Se guarda la información del usuario en la sesión
    $_SESSION['usuario'] = [
        'id' => $inserta->insert_id, 
        'correo' => $correo,
        'usuario' => $usuario
    ];
    $_SESSION['foto_perfil'] = $foto_perfil;
    
    header("Location: login.php?registro=exitoso");
    exit();
}
    $consulta->close();
    $inserta->close();
}

$conn->close();
?>
