<?php
include 'conexion.php';

$cliente = $_POST['cliente'];
$nombre = $_POST['nombre_estrategia'];
$descripcion = $_POST['descripcion'];
$objetivos = $_POST['objetivos'];
$tipo = $_POST['tipo_campaña'];
$presupuesto = $_POST['presupuesto'];
$fecha_inicio = $_POST['fecha_inicio'];
$fecha_fin = $_POST['fecha_fin'];
$kpis = $_POST['kpis'];
$plataformas = $_POST['plataformas'];
$audiencia = $_POST['audiencia'];

$sql = "INSERT INTO estrategias (cliente, nombre, descripcion, objetivos, tipo, presupuesto, fecha_inicio, fecha_fin, kpis, plataformas, audiencia)
VALUES ('$cliente', '$nombre', '$descripcion', '$objetivos', '$tipo', '$presupuesto', '$fecha_inicio', '$fecha_fin', '$kpis', '$plataformas', '$audiencia')";

if ($conn->query($sql) === TRUE) {
  echo "Guardado";
} else {
  echo "Error: " . $conn->error;
}
?>
