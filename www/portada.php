<?php
session_start();

require_once 'vendor/autoload.php';
include("conexion.php");

// --- BLOQUE DE CONSULTAS ---
$conn = conectar(); // Llamada a la nueva función

// 1. Obtener el logo
$resLogo = $conn->query("SELECT ruta_foto FROM imagenes WHERE id_noticia IS NULL LIMIT 1");
$logo = $resLogo->fetch_assoc();

// 2. Obtener noticias para la portada
$sql = "SELECT n.id, n.titulo, ANY_VALUE(i.ruta_foto) as ruta_foto 
        FROM noticias n 
        LEFT JOIN imagenes i ON n.id = i.id_noticia 
        WHERE n.id IS NOT NULL AND n.publicado = 1
        GROUP BY n.id";
$result = $conn->query($sql);

$noticias = [];
while($row = $result->fetch_assoc()) {
    $noticias[] = $row;
}

$conn->close(); // Cerramos la conexión manualmente
// ---------------------------

$loader = new \Twig\Loader\FilesystemLoader('plantillas');
$twig   = new \Twig\Environment($loader);

echo $twig->render('portada.html', [
    'noticias' => $noticias,
    'logo'     => $logo['ruta_foto'],
    'session'  => $_SESSION
]);
?>