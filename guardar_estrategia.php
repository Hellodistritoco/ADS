<?php
$conexion = new mysqli("localhost", "root", "", "ads_manager_pro");

if ($conexion->connect_error) {
  die("Conexión fallida: " . $conexion->connect_error);
}

$cliente_id = $_POST['cliente_id'];
$nombre_estrategia = $_POST['nombre_estrategia'];
$tipo = $_POST['tipo_estrategia'];
$objetivos = $_POST['objetivos'];
$campania = $_POST['tipo_campania'];
$kpis = $_POST['kpis'];
$plataformas = $_POST['plataformas'];
$audiencia = $_POST['audiencia'];

$sql = "INSERT INTO estrategias (cliente_id, nombre_estrategia, tipo_estrategia, objetivos, tipo_campania, kpis, plataformas, audiencia)
        VALUES ('$cliente_id', '$nombre_estrategia', '$tipo', '$objetivos', '$campania', '$kpis', '$plataformas', '$audiencia')";

if ($conexion->query($sql) === TRUE) {
  header("Location: estrategia.html?exito=1");
} else {
  echo "Error: " . $sql . "<br>" . $conexion->error;
}

$conexion->close();
?>
