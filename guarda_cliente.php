<?php
$conexion = new mysqli("localhost", "root", "", "ads_manager_pro");

if ($conexion->connect_error) {
  die("Conexión fallida: " . $conexion->connect_error);
}

$fecha = $_POST['fecha'];
$nombre = $_POST['nombre_cliente'];
$presupuesto = $_POST['presupuesto'];
$obj = $_POST['objetivo'];
$manager = $_POST['manejador'];

$sql = "INSERT INTO clientes (fecha, nombre_cliente, presupuesto, objetivo, manejado_por)
        VALUES ('$fecha', '$nombre', '$presupuesto', '$obj', '$manager')";

if ($conexion->query($sql) === TRUE) {
  header("Location: clientes.html?exito=1");
} else {
  echo "Error: " . $sql . "<br>" . $conexion->error;
}

$conexion->close();
?>
