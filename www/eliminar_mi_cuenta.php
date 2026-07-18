<?php
session_start();
include("conexion.php");
$conn = conectar();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$user_rol = $_SESSION['user_rol'];

// 1. VERIFICACIÓN DE SEGURIDAD PARA EL ÚLTIMO ROOT
if ($user_rol == 'root') { // Asumiendo que 1 es Root
    $resCount = $conn->query("SELECT COUNT(*) as total FROM usuarios WHERE id_rol = 1");
    $countData = $resCount->fetch_assoc();

    if ($countData['total'] <= 1) {
        // No permitimos borrar si es el único root

        $conn->close();
        header("Location: mi_perfil.php?error=ultimo_root");
        exit;
    }
    else {
        // 2. PROCEDER AL BORRADO
        // El ON DELETE CASCADE en la BD debería borrar sus comentarios asociados automáticamente
        $sql = "DELETE FROM usuarios WHERE id = $user_id";

        if ($conn->query($sql)) {
            // 3. CERRAR SESIÓN Y REDIRIGIR
            session_destroy();
            header("Location: portada.php?mensaje=cuenta_eliminada");
            exit;
        } else {
            header("Location: mi_perfil.php?error=error_borrado");
        }

        $conn->close();
    }
} else{
        // 2. PROCEDER AL BORRADO
        // El ON DELETE CASCADE en la BD debería borrar sus comentarios asociados automáticamente
        $sql = "DELETE FROM usuarios WHERE id = $user_id";

        if ($conn->query($sql)) {
            // 3. CERRAR SESIÓN Y REDIRIGIR
            session_destroy();
            header("Location: portada.php?mensaje=cuenta_eliminada");
            exit;
        } else {
            header("Location: mi_perfil.php?error=error_borrado");
        }

        $conn->close();
}


?>