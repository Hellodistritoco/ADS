<?php
include 'config.php';

// Configurar header para JSON
header('Content-Type: application/json');

// Consultar todas las estrategias
$sql = "SELECT 
    e.*,
    c.nombre_cliente 
FROM estrategias e 
INNER JOIN clientes c ON e.cliente_id = c.id 
ORDER BY e.fecha_creacion DESC";

$result = $conexion->query($sql);

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

$conexion->close();
?>