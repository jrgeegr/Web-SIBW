<?php
include("conexion.php");
$conn = conectar();

// Indicamos al navegador que la respuesta va a ser JSON puro
header('Content-Type: application/json');

// Recogemos lo que el usuario va tecleando
$termino = isset($_GET['q']) ? $conn->real_escape_string($_GET['q']) : '';

$datos = [];

if (!empty($termino)) {
    // Buscamos coincidencias en títulos de noticias QUE ESTÉN PUBLICADAS
    $sql = "SELECT id, titulo FROM noticias WHERE publicado = 1 AND titulo LIKE '%$termino%' LIMIT 5";
    $result = $conn->query($sql);
    
    while ($row = $result->fetch_assoc()) {
        $datos[] = [
            'id' => $row['id'],
            'titulo' => $row['titulo']
        ];
    }
}

$conn->close();

// Enviamos el array convertido en JSON para que JavaScript lo procese
echo json_encode($datos);
?>