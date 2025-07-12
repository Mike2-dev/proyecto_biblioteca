<?php
session_start();
require_once 'db.php';

$correo = trim($_POST['correo']);// Se obtiene el correo del formulario 
$clave = $_POST['clave'];// Se obtiene al contraseña del formulario 
// Se prepara la consulta para buscar al usuario por su correo en la base de datos
$stmt = $conn->prepare("SELECT id, correo, clave, usuario FROM usuarios WHERE correo = ?");
$stmt->bind_param("s", $correo);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 1) {
    $row = $resultado->fetch_assoc();
 // se verifica que la contraseña ingresada coincida con la guardada en la base de datos
    if (password_verify($clave, $row['clave'])) {
       // Si todo coincide, se guarda la información del usuario en la sesión 
        $_SESSION['usuario'] = [
            'id' => $row['id'],
            'correo' => $row['correo'],
            'usuario' => $row['usuario']
        ];

        header("Location: biblioteca.php");
        exit();
    } else {
        // Si la contraseña no es válida, redirige al login con mensaje de error
        header("Location: login.php?error=clave_incorrecta");
        exit();
    }
} else {
    // Si el correo ya esta regitrado, redirige al login con mensaje de error
      $stmt->close();
    $conn->close();
    header("Location: login.php?error=usuario_no_encontrado");
    exit();
}
?>
