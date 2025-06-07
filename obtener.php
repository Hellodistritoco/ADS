<?php
include 'conexion.php';

$result = $conn->query("SELECT * FROM estrategias ORDER BY id DESC");
$datos = [];

while ($fila = $result->fetch_assoc()) {
  $datos[] = $fila;
}

echo json_encode($datos);
?>
