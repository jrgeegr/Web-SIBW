<?php
session_start();
session_unset();
session_destroy(); // Borra toda la información de la sesión
header("Location: portada.php");
exit;