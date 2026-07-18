<?php
session_start();

require_once 'vendor/autoload.php';
include("conexion.php");

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// --- BLOQUE DE CONSULTAS ---
$conn = conectar(); //

$resLogo = $conn->query("SELECT ruta_foto FROM imagenes WHERE id_noticia IS NULL LIMIT 1");
$logo = $resLogo->fetch_assoc();

$noticia = null;
$imagenes = [];

if ($id > 0) {
    // Datos de la noticia
    $sqlNoticia = "SELECT n.*, l.nombre as nombre_lugar 
                   FROM noticias n 
                   JOIN localidades l ON n.lugar_id = l.id 
                   WHERE n.id = $id";
    $noticia = $conn->query($sqlNoticia)->fetch_assoc();

    // Imágenes de la noticia
    $resImgs = $conn->query("SELECT ruta_foto, titulo_foto FROM imagenes WHERE id_noticia = $id");
    while($row = $resImgs->fetch_assoc()) {
        $imagenes[] = $row;
    }
}

$conn->close(); //
// ---------------------------

$loader = new \Twig\Loader\FilesystemLoader('plantillas');
$twig   = new \Twig\Environment($loader);

echo $twig->render('noticia_imprimir.html', [
    'noticia'  => $noticia,
    'imagenes' => $imagenes,
    'logo'     => $logo['ruta_foto']
]);
?>