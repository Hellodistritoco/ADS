<?php
include 'config.php';

if ($conexion->connect_error) {
  die("Conexión fallida: " . $conexion->connect_error);
}

// Recibir datos del formulario
$cliente_id = $_POST['cliente_id'];
$estrategia_id = $_POST['estrategia_id'];
$cliente_nombre = $_POST['cliente_nombre'];
$estrategia_nombre = $_POST['estrategia_nombre'];
$fecha_inicio_analisis = $_POST['fecha_inicio_analisis'];
$fecha_fin_analisis = $_POST['fecha_fin_analisis'];
$funciono_bien = $_POST['funciono_bien'];
$no_funciono = $_POST['no_funciono'];
$propuestas_mejora = $_POST['propuestas_mejora'];
$impacto_esperado = $_POST['impacto_esperado'];
$prioridad = $_POST['prioridad'];
$metrica_principal = $_POST['metrica_principal'];
$responsable_implementacion = $_POST['responsable_implementacion'];
$fecha_limite = $_POST['fecha_limite'];
$presupuesto_adicional = $_POST['presupuesto_adicional'];
$observaciones = $_POST['observaciones'];

// Preparar consulta SQL
$sql = "INSERT INTO optimizaciones (
    cliente_id,
    estrategia_id,
    cliente_nombre,
    estrategia_nombre,
    fecha_inicio_analisis,
    fecha_fin_analisis,
    funciono_bien,
    no_funciono,
    propuestas_mejora,
    impacto_esperado,
    prioridad,
    metrica_principal,
    responsable_implementacion,
    fecha_limite,
    presupuesto_adicional,
    observaciones,
    fecha_creacion
) VALUES (
    ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW()
)";

// Preparar y ejecutar la consulta
$stmt = $conexion->prepare($sql);
$stmt->bind_param(
    "iissssssssssssds",
    $cliente_id,
    $estrategia_id,
    $cliente_nombre,
    $estrategia_nombre,
    $fecha_inicio_analisis,
    $fecha_fin_analisis,
    $funciono_bien,
    $no_funciono,
    $propuestas_mejora,
    $impacto_esperado,
    $prioridad,
    $metrica_principal,
    $responsable_implementacion,
    $fecha_limite,
    $presupuesto_adicional,
    $observaciones
);

if ($stmt->execute()) {
  // Redirigir con parámetro de éxito
  header("Location: optimizacion.html?exito=1");
} else {
  echo "Error: " . $stmt->error;
}

$stmt->close();
$conexion->close();
?>