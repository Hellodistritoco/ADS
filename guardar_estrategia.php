<?php
include 'config.php';

if ($conexion->connect_error) {
  die("Conexi¨®n fallida: " . $conexion->connect_error);
}

// Recibir datos del formulario
$cliente_id = $_POST['cliente_id'];
$presupuesto_cliente = $_POST['presupuesto_cliente'];
$objetivo_cliente = $_POST['objetivo_cliente'];
$presupuesto_estrategia = $_POST['presupuesto_estrategia'];
$nombre_estrategia = $_POST['nombre_estrategia'];
$tipo_estrategia = $_POST['tipo_estrategia'];
$tipo_campana = $_POST['tipo_campana'];
$objetivos_estrategia = $_POST['objetivos_estrategia'];
$kpis = $_POST['kpis'];
$plataformas = $_POST['plataformas'];
$audiencia = $_POST['audiencia'];
$fecha_inicio = $_POST['fecha_inicio'];
$fecha_fin = $_POST['fecha_fin'];
$responsable = $_POST['responsable'];

// Preparar consulta SQL
$sql = "INSERT INTO estrategias (
    cliente_id, 
    presupuesto_cliente, 
    objetivo_cliente, 
    presupuesto_estrategia, 
    nombre_estrategia, 
    tipo_estrategia, 
    tipo_campana, 
    objetivos_estrategia, 
    kpis, 
    plataformas, 
    audiencia, 
    fecha_inicio, 
    fecha_fin, 
    responsable, 
    fecha_creacion
) VALUES (
    '$cliente_id', 
    '$presupuesto_cliente', 
    '$objetivo_cliente', 
    '$presupuesto_estrategia', 
    '$nombre_estrategia', 
    '$tipo_estrategia', 
    '$tipo_campana', 
    '$objetivos_estrategia', 
    '$kpis', 
    '$plataformas', 
    '$audiencia', 
    '$fecha_inicio', 
    '$fecha_fin', 
    '$responsable', 
    NOW()
)";

if ($conexion->query($sql) === TRUE) {
  // Redirigir con par¨¢metro de ¨¦xito
  header("Location: estrategia.html?exito=1");
} else {
  echo "Error: " . $sql . "<br>" . $conexion->error;
}

$conexion->close();
?>