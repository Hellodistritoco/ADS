<?php
// Verificar que se proporcionen los parámetros necesarios
if (!isset($_GET['archivo']) || !isset($_GET['nombre'])) {
    die('Parámetros inválidos');
}

$ruta_archivo = $_GET['archivo'];
$nombre_original = $_GET['nombre'];

// Verificar que el archivo existe
if (!file_exists($ruta_archivo)) {
    die('Archivo no encontrado');
}

// Verificar que el archivo está en el directorio permitido
$directorio_permitido = realpath('uploads/csv/');
$ruta_real = realpath($ruta_archivo);

if (strpos($ruta_real, $directorio_permitido) !== 0) {
    die('Acceso no autorizado');
}

// Configurar headers para descarga
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="' . $nombre_original . '"');
header('Content-Length: ' . filesize($ruta_archivo));

// Leer y enviar el archivo
readfile($ruta_archivo);
exit();
?>