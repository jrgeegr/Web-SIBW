<?php
session_start();
require_once 'vendor/autoload.php';
include("conexion.php");

$loader = new \Twig\Loader\FilesystemLoader('plantillas');
$twig   = new \Twig\Environment($loader);

$error = null;

// Si recibimos datos por POST, procesamos el registro
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = conectar();
    
    $nombre = $conn->real_escape_string($_POST['nombre']);
    $email  = $conn->real_escape_string($_POST['email']);
    $pass   = $_POST['pass'];
    $pass2  = $_POST['pass2'];

    // 1. Validar que las contraseñas coinciden (seguridad en servidor)
    if ($pass !== $pass2) {
        $error = "Las contraseñas no coinciden.";
    } else {
        // 2. Comprobar si el email ya existe (Requisito de la práctica)
        $checkEmail = $conn->query("SELECT id FROM usuarios WHERE email = '$email'");
        if ($checkEmail->num_rows > 0) {
            $error = "El correo electrónico ya está registrado.";
        } else {
            // 3. Cifrar contraseña y guardar
            $passHash = password_hash($pass, PASSWORD_DEFAULT);
            
            // Asumimos que el ID del rol 'registrado' es el 4
            $sql = "INSERT INTO usuarios (email, password, nombre_completo, id_rol) 
                    VALUES ('$email', '$passHash', '$nombre', 4)";
            
            if ($conn->query($sql)) {
                $conn->close();
                header("Location: login.php?registro=exito");
                exit;
            } else {
                $error = "Error en el registro: " . $conn->error;
            }
        }
    }
    $conn->close();
}

// Obtener logo para la cabecera (reutilizando tu lógica de la P3)
$conn = conectar();
$resLogo = $conn->query("SELECT ruta_foto FROM imagenes WHERE id_noticia IS NULL LIMIT 1");
$logo = $resLogo->fetch_assoc();
$conn->close();

// Renderizar plantilla
echo $twig->render('registro.html', [
    'logo'    => $logo['ruta_foto'],
    'error'   => $error,
    'session' => $_SESSION
]);