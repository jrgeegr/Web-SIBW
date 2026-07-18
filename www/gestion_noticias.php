<?php
session_start();
if (!isset($_SESSION['user_rol']) || !in_array($_SESSION['user_rol'], ['gestor', 'root'])) {
    header("Location: portada.php");
    exit;
}

require_once 'vendor/autoload.php';
include("conexion.php");
$conn = conectar();

// --- NUEVO: ACCIÓN AJAX PARA CAMBIAR EL ESTADO DE PUBLICACIÓN ---
if (isset($_GET['cambiar_publicado'])) {
    header('Content-Type: application/json');
    
    $id_not = intval($_GET['id_noticia']);
    $estado = intval($_GET['estado']); // Recibiremos 1 o 0
    
    $sql = "UPDATE noticias SET publicado = $estado WHERE id = $id_not";
    
    if ($conn->query($sql)) {
        echo json_encode(['status' => 'success', 'mensaje' => 'Estado actualizado']);
    } else {
        echo json_encode(['status' => 'error', 'mensaje' => $conn->error]);
    }
    
    $conn->close();
    exit; // Terminamos la ejecución inmediatamente
}

// Acción de borrar noticia (Se queda igual)
if (isset($_GET['borrar'])) {
    $id_not = intval($_GET['borrar']);
    $resImgs = $conn->query("SELECT ruta_foto FROM imagenes WHERE id_noticia = $id_not");
    while ($img = $resImgs->fetch_assoc()) {
        if (file_exists($img['ruta_foto'])) {
            unlink($img['ruta_foto']);
        }
    }
    $conn->query("DELETE FROM noticias WHERE id = $id_not");
    header("Location: gestion_noticias.php");
    exit;
}

// --- LÓGICA DE BÚSQUEDA TRIPLE ---
$busqueda_titulo  = isset($_GET['busqueda_titulo']) ? $conn->real_escape_string($_GET['busqueda_titulo']) : '';
$busqueda_cuerpo  = isset($_GET['busqueda_cuerpo']) ? $conn->real_escape_string($_GET['busqueda_cuerpo']) : '';
$busqueda_hashtag = isset($_GET['busqueda_hashtag']) ? $conn->real_escape_string($_GET['busqueda_hashtag']) : '';

$condiciones = " WHERE 1=1 ";

if (!empty($busqueda_titulo)) {
    $condiciones .= " AND n.titulo LIKE '%$busqueda_titulo%' ";
}
if (!empty($busqueda_cuerpo)) {
    $condiciones .= " AND n.cuerpo LIKE '%$busqueda_cuerpo%' ";
}
if (!empty($busqueda_hashtag)) {
    $condiciones .= " AND h.nombre LIKE '%$busqueda_hashtag%' ";
}

// MODIFICADO: Añadimos n.publicado a la selección SELECT
$sql = "SELECT n.id, n.titulo, n.fecha_pub, n.tipo, n.publicado, 
               GROUP_CONCAT(h.nombre SEPARATOR ', ') as lista_hashtags
        FROM noticias n
        LEFT JOIN noticias_hashtags nh ON n.id = nh.id_noticia
        LEFT JOIN hashtags h ON nh.id_hashtag = h.id
        $condiciones
        GROUP BY n.id
        ORDER BY n.fecha_pub DESC";

$result = $conn->query($sql);

$noticias = [];
while ($row = $result->fetch_assoc()) {
    $noticias[] = $row;
}

// Si nos piden los datos de búsqueda por AJAX (Paso 3)
if (isset($_GET['ajax']) && $_GET['ajax'] == '1') {
    header('Content-Type: application/json');
    echo json_encode($noticias);
    $conn->close();
    exit;
}

include("config_logo.php");
$conn->close();

$loader = new \Twig\Loader\FilesystemLoader('plantillas');
$twig   = new \Twig\Environment($loader);

echo $twig->render('gestion_noticias.html', [
    'noticias'         => $noticias,
    'busqueda_titulo'  => $busqueda_titulo,
    'busqueda_cuerpo'  => $busqueda_cuerpo,
    'busqueda_hashtag' => $busqueda_hashtag,
    'session'          => $_SESSION,
    'logo'             => $logo
]);
?>