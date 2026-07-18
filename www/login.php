<?php
session_start();
require_once 'vendor/autoload.php';
include("conexion.php");

$loader = new \Twig\Loader\FilesystemLoader('plantillas');
$twig   = new \Twig\Environment($loader);

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = conectar();
    
    $email = $conn->real_escape_string($_POST['email']);
    $pass  = $_POST['pass'];

    // Buscamos al usuario y obtenemos el nombre de su rol haciendo un JOIN con la tabla roles
    $sql = "SELECT u.*, r.nombre as nombre_rol 
            FROM usuarios u 
            JOIN roles r ON u.id_rol = r.id 
            WHERE u.email = '$email'";
    
    $result = $conn->query($sql);
    
    if ($user = $result->fetch_assoc()) {
        // Verificamos si la contraseña coincide con el hash almacenado
        if (password_verify($pass, $user['password'])) {
            // ¡Éxito! Creamos las variables de sesión
            $_SESSION['user_id']   = $user['id'];
            $_SESSION['user_name'] = $user['nombre_completo'];
            $_SESSION['user_rol']  = $user['nombre_rol']; // 'root', 'gestor', 'moderador', 'registrado'
            
            $conn->close();
            header("Location: portada.php");
            exit;
        } else {
            $error = "Contraseña incorrecta.";
        }
    } else {
        $error = "El usuario no existe.";
    }
    $conn->close();
}

// Obtener logo para la cabecera
$conn = conectar();
$resLogo = $conn->query("SELECT ruta_foto FROM imagenes WHERE id_noticia IS NULL LIMIT 1");
$logo = $resLogo->fetch_assoc();
$conn->close();

echo $twig->render('login.html', [
    'logo'    => $logo['ruta_foto'],
    'error'   => $error,
    'session' => $_SESSION,
    'registro_exito' => isset($_GET['registro']) && $_GET['registro'] === 'exito'
]);