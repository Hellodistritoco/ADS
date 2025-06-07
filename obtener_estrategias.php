<?php
include 'config.php';

// Configurar header para JSON
header('Content-Type: application/json');

// Obtener el ID del cliente desde la URL
$cliente_id = isset($_GET['cliente_id']) ? $_GET['cliente_id'] : '';

if (empty($cliente_id)) {
    echo json_encode([]);
    exit;
}

// Consultar estrategias del cliente específico
$sql = "SELECT 
    e.*,
    c.nombre_cliente 
FROM estrategias e 
INNER JOIN clientes c ON e.cliente_id = c.id 
WHERE e.cliente_id = ? 
ORDER BY e.fecha_creacion DESC";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $cliente_id);
$stmt->execute();
$result = $stmt->get_result();

$estrategias = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $estrategias[] = array(
            'id' => $row['id'],
            'cliente_id' => $row['cliente_id'],
            'nombre_cliente' => $row['nombre_cliente'],
            'presupuesto_cliente' => $row['presupuesto_cliente'],
            'objetivo_cliente' => $row['objetivo_cliente'],
            'presupuesto_estrategia' => $row['presupuesto_estrategia'],
            'nombre_estrategia' => $row['nombre_estrategia'],
            'tipo_estrategia' => $row['tipo_estrategia'],
            'tipo_campana' => $row['tipo_campana'],
            'objetivos_estrategia' => $row['objetivos_estrategia'],
            'kpis' => $row['kpis'],
            'plataformas' => $row['plataformas'],
            'audiencia' => $row['audiencia'],
            'fecha_inicio' => $row['fecha_inicio'],
            'fecha_fin' => $row['fecha_fin'],
            'responsable' => $row['responsable'],
            'fecha_creacion' => $row['fecha_creacion']
        );
    }
}

// Devolver JSON
echo json_encode($estrategias);

$stmt->close();
$conexion->close();
?>