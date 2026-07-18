<?php
session_start();
if (!isset($_SESSION['user_rol']) || !in_array($_SESSION['user_rol'], ['gestor', 'root'])) {
    header("Location: portada.php");
    exit;
}

require_once 'vendor/autoload.php';
include("conexion.php");
$conn = conectar();

include("config_logo.php"); // Carga $logo y $conn

$id_not = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
$mensaje = "";

// 1. PROCESAR GUARDADO
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo       = $conn->real_escape_string($_POST['titulo']);
    $fecha_pub    = $conn->real_escape_string($_POST['fecha_pub']);
    $tipo         = $conn->real_escape_string($_POST['tipo']);
    $nombre_lugar = $conn->real_escape_string($_POST['lugar_nombre']); 
    $concejalia   = $conn->real_escape_string($_POST['concejalia']);
    $responsable  = $conn->real_escape_string($_POST['responsable']);
    $cuerpo       = $conn->real_escape_string($_POST['cuerpo']);
    
    // CORRECCIÓN: Definimos la variable correctamente capturando el POST
    $hashtags_raw = isset($_POST['hashtags']) ? $_POST['hashtags'] : "";
    
    // Buscar o crear lugar
    $resL = $conn->query("SELECT id FROM localidades WHERE nombre = '$nombre_lugar' LIMIT 1");
    $filaL = $resL->fetch_assoc();
    if ($filaL) {
        $lugar_id = $filaL['id'];
    } else {
        $conn->query("INSERT INTO localidades (nombre) VALUES ('$nombre_lugar')");
        $lugar_id = $conn->insert_id;
    }

    if ($id_not == 0) { // NUEVA NOTICIA
        $sql = "INSERT INTO noticias (titulo, fecha_pub, tipo, lugar_id, concejalia, responsable, cuerpo) 
                VALUES ('$titulo', '$fecha_pub', '$tipo', $lugar_id, '$concejalia', '$responsable', '$cuerpo')";
        if ($conn->query($sql)) {
            $id_not = $conn->insert_id;
            subirImagenes($id_not, $conn);
            
            // CORRECCIÓN: Nombre de función correcto y variable definida
            gestionarHashtags($id_not, $hashtags_raw, $conn);

            header("Location: editar_noticia.php?exito=1");
            exit;
        }
    } else { // EDITAR EXISTENTE
        $sql = "UPDATE noticias SET 
                titulo='$titulo', fecha_pub='$fecha_pub', tipo='$tipo', 
                lugar_id=$lugar_id, concejalia='$concejalia', 
                responsable='$responsable', cuerpo='$cuerpo' 
                WHERE id=$id_not";
        
        if ($conn->query($sql)) {
            subirImagenes($id_not, $conn);
            
            // CORRECCIÓN: Añadimos la llamada aquí también para que al editar se guarden
            gestionarHashtags($id_not, $hashtags_raw, $conn);
            
            $mensaje = "Noticia actualizada con éxito.";
        }
    }
}

/**
 * Función para procesar y vincular hashtags a la noticia
 */
function gestionarHashtags($id_noticia, $hashtags_str, $conn) {
    // 1. Limpiar asociaciones previas para evitar duplicados al editar
    $conn->query("DELETE FROM noticias_hashtags WHERE id_noticia = $id_noticia");

    // 2. Separar por comas o espacios y limpiar
    $tags = preg_split('/[\s,]+/', $hashtags_str, -1, PREG_SPLIT_NO_EMPTY);

    foreach ($tags as $tag) {
        $tag = trim($tag, "# "); // Quitamos el símbolo # si lo lleva
        if (!empty($tag)) {
            $tag = $conn->real_escape_string($tag);
            
            // Comprobar si el hashtag existe en la tabla maestra
            $resH = $conn->query("SELECT id FROM hashtags WHERE nombre = '$tag'");
            if ($resH && $resH->num_rows > 0) {
                $h_data = $resH->fetch_assoc();
                $id_hashtag = $h_data['id'];
            } else {
                // Crear el hashtag si es nuevo
                $conn->query("INSERT INTO hashtags (nombre) VALUES ('$tag')");
                $id_hashtag = $conn->insert_id;
            }

            // Crear la relación en la tabla intermedia
            $conn->query("INSERT INTO noticias_hashtags (id_noticia, id_hashtag) VALUES ($id_noticia, $id_hashtag)");
        }
    }
}

/**
 * Función auxiliar para gestionar la subida múltiple con nombres únicos
 */
function subirImagenes($id_not, $conn) {
    if (!empty($_FILES['fotos']['name'][0])) {
        $total_archivos = count($_FILES['fotos']['name']);

        for ($i = 0; $i < $total_archivos; $i++) {
            $error = $_FILES['fotos']['error'][$i];
            
            if ($error === UPLOAD_ERR_OK) {
                $tmp_name        = $_FILES['fotos']['tmp_name'][$i];
                $nombre_original = basename($_FILES['fotos']['name'][$i]);
                $extension       = pathinfo($nombre_original, PATHINFO_EXTENSION);
                $solo_nombre     = pathinfo($nombre_original, PATHINFO_FILENAME);

                // Evitar colisión de nombres con marca de tiempo y sufijo aleatorio
                $nuevo_nombre = $solo_nombre . "_" . time() . "_" . $i . "." . $extension;
                $ruta_destino = "img/" . $nuevo_nombre;
                
                if (move_uploaded_file($tmp_name, $ruta_destino)) {
                    $conn->query("INSERT INTO imagenes (id_noticia, ruta_foto) VALUES ($id_not, '$ruta_destino')");
                }
            }
        }
    }
}

// Capturamos el mensaje de éxito de la redirección si existe
if (isset($_GET['exito'])) {
    $mensaje = "Noticia creada con éxito.";
}

// --- LÓGICA DE BORRADO DE IMAGEN ---
if (isset($_GET['borrar_img'])) {
    $id_img = intval($_GET['borrar_img']);
    
    // Opcional: Obtener la ruta para borrar el archivo del servidor físicamente
    $resPath = $conn->query("SELECT ruta_foto FROM imagenes WHERE id = $id_img");
    if ($rowPath = $resPath->fetch_assoc()) {
        if (file_exists($rowPath['ruta_foto'])) {
            unlink($rowPath['ruta_foto']); // Borra el archivo físico en img/
        }
    }
    
    // Borrar registro en la base de datos
    $conn->query("DELETE FROM imagenes WHERE id = $id_img");
    header("Location: editar_noticia.php?id=$id_not");
    exit;
}

// --- OBTENER IMÁGENES ACTUALES ---
$imagenes = [];
if ($id_not > 0) {
    $resImgs = $conn->query("SELECT id, ruta_foto FROM imagenes WHERE id_noticia = $id_not");
    while ($row = $resImgs->fetch_assoc()) {
        $imagenes[] = $row;
    }
}

// 2. OBTENER DATOS PARA EL FORMULARIO
$noticia = ['id' => 0, 'titulo' => '', 'fecha_pub' => date('Y-m-d'), 'tipo' => '', 'lugar_id' => 0, 'concejalia' => '', 'responsable' => '', 'cuerpo' => ''];
if ($id_not > 0) {
    // Añadimos el JOIN con la tabla localidades para traer el nombre del lugar actual
    $res = $conn->query("SELECT n.*, l.nombre as nombre_lugar 
                         FROM noticias n 
                         LEFT JOIN localidades l ON n.lugar_id = l.id 
                         WHERE n.id = $id_not");
    if ($res && $res->num_rows > 0) {
        $noticia = $res->fetch_assoc();
    }
}

// Lista de localidades para el <select>
$resLocs = $conn->query("SELECT id, nombre FROM localidades ORDER BY nombre ASC");
$localidades = [];
while($row = $resLocs->fetch_assoc()) { $localidades[] = $row; }



if ($id_not > 0) {
    $tags_array = [];
    $resH = $conn->query("SELECT h.nombre 
                          FROM hashtags h 
                          JOIN noticias_hashtags nh ON h.id = nh.id_hashtag 
                          WHERE nh.id_noticia = $id_not");

    while($h = $resH->fetch_assoc()) {
        $tags_array[] = $h['nombre'];
    }
    // Metemos el string "tag1, tag2" en el array noticia para que Twig lo use
    $noticia['hashtags_string'] = implode(', ', $tags_array);
}

$conn->close();

$loader = new \Twig\Loader\FilesystemLoader('plantillas');
$twig   = new \Twig\Environment($loader);

echo $twig->render('editar_noticia.html', [
    'noticia'     => $noticia,
    'localidades' => $localidades,
    'mensaje'     => $mensaje,
    'session'     => $_SESSION,
    'logo'        => $logo,
    'imagenes'    => $imagenes
]);