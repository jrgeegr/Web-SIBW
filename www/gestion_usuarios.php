<?php
session_start();

// 1. PROTECCIÓN DE ACCESO: Solo el rol 1 (Root) puede entrar
// Asumimos que en el login guardaste el ID del rol en la sesión
if (!isset($_SESSION['user_rol']) || $_SESSION['user_rol'] !== 'root') {
    header("Location: portada.php");
    exit;
}

require_once 'vendor/autoload.php';
include("conexion.php");
$conn = conectar();

// Cargamos el logo (Asegúrate de que $conn ya esté definida antes de incluirlo)
include("config_logo.php");

$mensaje_error = "";
$mensaje_exito = "";

// 2. PROCESAR ACCIONES (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cambiar_rol'])) {
    $id_u = intval($_POST['id_usuario']);
    $nuevo_id_rol = intval($_POST['id_rol']);
    
    // Obtenemos el rol actual del usuario que se intenta modificar
    $resUser = $conn->query("SELECT id_rol FROM usuarios WHERE id = $id_u");
    $userActual = $resUser->fetch_assoc();

    // LÓGICA DE PROTECCIÓN: Si es Root (1) e intenta dejar de serlo (!= 1)
    if ($userActual['id_rol'] == 1 && $nuevo_id_rol != 1) {
        $resCount = $conn->query("SELECT COUNT(*) as total FROM usuarios WHERE id_rol = 1");
        $countData = $resCount->fetch_assoc();

        if ($countData['total'] <= 1) {
            header("Location: gestion_usuarios.php?error=ultimo_root");
            exit;
        }
    }

    // Si pasa la validación, actualizamos
    $sql = "UPDATE usuarios SET id_rol = $nuevo_id_rol WHERE id = $id_u";
    if ($conn->query($sql)) {
        header("Location: gestion_usuarios.php?exito=rol");
        exit;
    }
}

// 3. PROCESAR BORRADO (GET)
if (isset($_GET['borrar'])) {
    $id_u = intval($_GET['borrar']);
    
    // Comprobamos si el usuario a borrar es Root
    $resUser = $conn->query("SELECT id_rol FROM usuarios WHERE id = $id_u");
    $userActual = $resUser->fetch_assoc();

    if ($userActual['id_rol'] == 1) {
        $resCount = $conn->query("SELECT COUNT(*) as total FROM usuarios WHERE id_rol = 1");
        $countData = $resCount->fetch_assoc();
        
        if ($countData['total'] <= 1) {
            header("Location: gestion_usuarios.php?error=borrar_ultimo_root");
            exit;
        }
    }

    // No permitirse borrar a uno mismo si se accede por URL
    if ($id_u != $_SESSION['user_id']) {
        $conn->query("DELETE FROM usuarios WHERE id = $id_u");
        header("Location: gestion_usuarios.php?exito=borrado");
        exit;
    }
}

// 4. CAPTURAR MENSAJES DE FEEDBACK
if (isset($_GET['error'])) {
    if ($_GET['error'] == 'ultimo_root') $mensaje_error = "No puedes cambiar el rol al último Administrador Root.";
    if ($_GET['error'] == 'borrar_ultimo_root') $mensaje_error = "No puedes eliminar al único Administrador Root del sistema.";
}
if (isset($_GET['exito'])) {
    if ($_GET['exito'] == 'rol') $mensaje_exito = "Rol actualizado correctamente.";
    if ($_GET['exito'] == 'borrado') $mensaje_exito = "Usuario eliminado correctamente.";
}

// 5. OBTENER LISTADO DE USUARIOS
$res = $conn->query("SELECT id, email, nombre_completo, id_rol FROM usuarios ORDER BY nombre_completo ASC");
$usuarios = [];
while ($row = $res->fetch_assoc()) {
    $usuarios[] = $row;
}

$resCount = $conn->query("SELECT COUNT(*) as total FROM usuarios WHERE id_rol = 1");
$countData = $resCount->fetch_assoc();
$total_roots = $countData['total'];

$conn->close();

// 6. RENDERIZADO CON TWIG
$loader = new \Twig\Loader\FilesystemLoader('plantillas');
$twig   = new \Twig\Environment($loader);

echo $twig->render('gestion_usuarios.html', [
    'usuarios'      => $usuarios,
    'mensaje_error' => $mensaje_error,
    'mensaje_exito' => $mensaje_exito,
    'session'       => $_SESSION,
    'logo'          => $logo,
    'total_roots'   => $total_roots
]);