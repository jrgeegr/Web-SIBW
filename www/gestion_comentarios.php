<?php
session_start();
// 1. Protección: Solo moderadores o superior pueden entrar
if (!isset($_SESSION['user_rol']) || !in_array($_SESSION['user_rol'], ['moderador', 'root'])) {
    header("Location: portada.php");
    exit;
}

require_once 'vendor/autoload.php';
include("conexion.php");

$conn = conectar();
$error = null;

include("config_logo.php");

// Acción de borrar
if (isset($_GET['borrar'])) {
    $id_com = intval($_GET['borrar']);
    $conn->query("DELETE FROM comentarios WHERE id = $id_com");
}

// Lógica de búsqueda
$busqueda = isset($_GET['buscar']) ? $conn->real_escape_string($_GET['buscar']) : '';
$sql = "SELECT c.*, n.titulo as titulo_noticia FROM comentarios c 
        JOIN noticias n ON c.id_noticia = n.id 
        WHERE c.texto LIKE '%$busqueda%' 
        ORDER BY c.fecha DESC";

$result = $conn->query($sql);
$comentarios = [];
while ($row = $result->fetch_assoc()) {
    $comentarios[] = $row;
}

$conn->close();

$loader = new \Twig\Loader\FilesystemLoader('plantillas');
$twig   = new \Twig\Environment($loader);

echo $twig->render('gestion_comentarios.html', [
    'comentarios' => $comentarios,
    'busqueda'    => $busqueda,
    'session'     => $_SESSION,
    'logo'        => $logo
]);