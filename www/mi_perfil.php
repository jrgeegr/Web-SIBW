<?php
session_start();
// Protección: Solo usuarios logueados
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once 'vendor/autoload.php';
include("conexion.php");
$conn = conectar();

include("config_logo.php");

$id_user = $_SESSION['user_id'];
$mensaje = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $email  = $conn->real_escape_string($_POST['email']);
    
    // Actualizar datos básicos
    $sql = "UPDATE usuarios SET nombre_completo = '$nombre', email = '$email' WHERE id = $id_user";
    
    // Si el usuario escribe una nueva contraseña, la actualizamos
    if (!empty($_POST['pass'])) {
        $passHash = password_hash($_POST['pass'], PASSWORD_DEFAULT);
        $sql = "UPDATE usuarios SET nombre_completo = '$nombre', email = '$email', password = '$passHash' WHERE id = $id_user";
    }
    
    if ($conn->query($sql)) {
        $_SESSION['user_name'] = $nombre; // Actualizamos el nombre en la sesión
        $mensaje = "Datos actualizados correctamente.";
    }
}

// Obtener datos actuales
$res = $conn->query("SELECT * FROM usuarios WHERE id = $id_user");
$usuario = $res->fetch_assoc();
$conn->close();

$loader = new \Twig\Loader\FilesystemLoader('plantillas');
$twig   = new \Twig\Environment($loader);

echo $twig->render('mi_perfil.html', [
    'usuario' => $usuario,
    'mensaje' => $mensaje,
    'session' => $_SESSION,
    'error'   => isset($_GET['error']) ? $_GET['error'] : null,
    'logo'    => $logo
]);