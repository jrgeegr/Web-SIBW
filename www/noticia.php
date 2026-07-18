<?php
function puede_moderar($session) {
    return isset($session['user_rol']) && in_array($session['user_rol'], ['moderador', 'gestor', 'root']);
}

session_start();

require_once 'vendor/autoload.php';
include("conexion.php");

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// --- BLOQUE 1: GUARDAR COMENTARIO (POST) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id > 0 && isset($_SESSION['user_id'])) {
    $conn = conectar(); // Abrimos para insertar
    
    $autor = $conn->real_escape_string($_POST['autor']);
    $email = $conn->real_escape_string($_POST['email']);
    $texto = $conn->real_escape_string($_POST['texto']);

    if (!empty($autor) && !empty($texto)) {
        $sqlInsert = "INSERT INTO comentarios (id_noticia, autor, email, texto) 
                      VALUES ($id, '$autor', '$email', '$texto')";
        $conn->query($sqlInsert);
    }
    
    $conn->close(); // Cerramos tras insertar
    header("Location: noticia.php?id=$id"); //Para que no se dupliquen los comentarios al cargar
    exit;
}

// --- BLOQUE 2: CARGA DE DATOS ---
$conn = conectar(); // Abrimos para consultar

$resLogo = $conn->query("SELECT ruta_foto FROM imagenes WHERE id_noticia IS NULL LIMIT 1");
$logo = $resLogo->fetch_assoc();

$noticia = null; $imagenes = []; $comentarios = []; $localidades = [];

if ($id > 0) {
    // Noticia
    $resNoticia = $conn->query("SELECT n.*, l.nombre as nombre_lugar FROM noticias n JOIN localidades l ON n.lugar_id = l.id WHERE n.id = $id");
    $noticia = $resNoticia->fetch_assoc();

    // Imágenes
    $resImgs = $conn->query("SELECT ruta_foto, titulo_foto FROM imagenes WHERE id_noticia = $id");
    while($row = $resImgs->fetch_assoc()) { $imagenes[] = $row; }

    // Comentarios
    $resCom = $conn->query("SELECT id, autor, texto, fecha, editado_por_moderador FROM comentarios WHERE id_noticia = $id ORDER BY fecha DESC");
    while($row = $resCom->fetch_assoc()) { $comentarios[] = $row; }

    // Localidades
    $resLocs = $conn->query("SELECT nombre FROM localidades");
    while($row = $resLocs->fetch_assoc()) { $localidades[] = $row['nombre']; }

    //Hastags
    $hashtags = [];
    $resHash = $conn->query("SELECT h.nombre 
                            FROM hashtags h 
                            JOIN noticias_hashtags nh ON h.id = nh.id_hashtag 
                            WHERE nh.id_noticia = $id");

    while($row = $resHash->fetch_assoc()) {
        $hashtags[] = $row;
    }
}

$conn->close(); // Cerramos antes del render
// ---------------------------

$loader = new \Twig\Loader\FilesystemLoader('plantillas');
$twig   = new \Twig\Environment($loader);

$puede_moderar = puede_moderar($_SESSION);

echo $twig->render('noticia.html', [
    'noticia' => $noticia,
    'imagenes' => $imagenes,
    'comentarios' => $comentarios,
    'logo' => $logo['ruta_foto'],
    'localidades' => $localidades,
    'session' => $_SESSION,
    'puede_moderar' => $puede_moderar,
    'hashtags'      => $hashtags
]);