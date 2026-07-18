<?php
session_start();
if (!isset($_SESSION['user_rol']) || !in_array($_SESSION['user_rol'], ['moderador', 'root'])) {
    header("Location: portada.php");
    exit;
}

require_once 'vendor/autoload.php';
include("conexion.php");
$conn = conectar();

include("config_logo.php");

// 1. Aseguramos el ID capturado
$id_com = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;

$mensaje_exito = "";

// 2. PROCESAR GUARDADO
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Capturamos el texto del textarea
    $texto_nuevo = $conn->real_escape_string($_POST['texto']);
    
    // OJO: Asegúrate de que el id_com esté definido aquí también
    $sql = "UPDATE comentarios SET 
            texto = '$texto_nuevo', 
            editado_por_moderador = 1 
            WHERE id = $id_com"; // <-- Usamos la variable capturada al inicio
    
    if ($conn->query($sql)) {
        $mensaje_exito = "Comentario actualizado correctamente.";
    } else {
        echo "Error en SQL: " . $conn->error; // Útil para depurar
    }
}

// 3. OBTENER DATOS (Para mostrar en el formulario)
$res = $conn->query("SELECT * FROM comentarios WHERE id = $id_com");
$comentario = $res->fetch_assoc();

$conn->close();

$loader = new \Twig\Loader\FilesystemLoader('plantillas');
$twig   = new \Twig\Environment($loader);

echo $twig->render('editar_comentario.html', [
    'comentario'    => $comentario,
    'mensaje_exito' => $mensaje_exito,
    'session'       => $_SESSION,
    'logo'          => $logo
]);