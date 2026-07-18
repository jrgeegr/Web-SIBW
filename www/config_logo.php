<?php
// Este archivo obtiene el logo y lo devuelve como array para Twig
$resLogo = $conn->query("SELECT ruta_foto FROM imagenes WHERE id_noticia IS NULL LIMIT 1");
$logoData = $resLogo->fetch_assoc();
$logo = $logoData ? $logoData['ruta_foto'] : 'img/logo_defecto.png'; // Fallback por si no hay logo
?>