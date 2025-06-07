<?php
include 'config.php';

// Configurar header para JSON
header('Content-Type: application/json');

// Obtener el ID de la métrica
$metrica_id = isset($_GET['metrica_id']) ? $_GET['metrica_id'] : '';

if (empty($metrica_id)) {
    echo json_encode([]);
    exit;
}

// Consultar los datos del CSV para esta métrica
$sql = "SELECT 
    fila_numero,
    datos_json
FROM metricas_datos 
WHERE metrica_id = ? 
ORDER BY fila_numero ASC";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $metrica_id);
$stmt->execute();
$result = $stmt->get_result();

$datos = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $datos[] = array(
            'fila_numero' => $row['fila_numero'],
            'datos_json' => $row['datos_json']
        );
    }
}

// Devolver JSON
echo json_encode($datos);

$stmt->close();
$conexion->close();
?>