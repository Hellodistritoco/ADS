<?php
$host = 'server71.web-hosting.com';
$dbname = 'hellfhpr_hello';
$username = 'hellfhpr_root';
$password = '+&cv^ju*I_q+';

// Crear conexión
$conexion = new mysqli($host, $username, $password, $dbname);

// Verificar conexión
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Configurar charset
$conexion->set_charset("utf8");
?>
