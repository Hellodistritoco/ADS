<?php
include 'config.php';

$fecha = $_POST['fecha'];
$nombre = $_POST['nombre_cliente'];
$presupuesto = $_POST['presupuesto'];
$obj = $_POST['objetivo'];
$manager = $_POST['manejador'];

$sql = "INSERT INTO clientes (fecha, nombre_cliente, presupuesto, objetivo, manejado_por)
        VALUES ('$fecha', '$nombre', '$presupuesto', '$obj', '$manager')";

if ($conexion->query($sql) === TRUE) {
  // Redirigir con parámetro de éxito
  header("Location: clientes.html?registro=exitoso");
} else {
  echo "Error: " . $sql . "<br>" . $conexion->error;
}

$conexion->close();
?>