<?php
include 'config.php';

// Configurar header para JSON
header('Content-Type: application/json');

try {
    // Verificar conexiиоn
    if ($conexion->connect_error) {
        throw new Exception("Error de conexiиоn: " . $conexion->connect_error);
    }

    // Consultar todas las optimizaciones con informaciиоn de clientes y estrategias
    $sql = "SELECT 
        o.*,
        c.nombre_cliente as cliente_nombre_db,
        e.nombre_estrategia as estrategia_nombre_db
    FROM optimizaciones o 
    LEFT JOIN clientes c ON o.cliente_id = c.id 
    LEFT JOIN estrategias e ON o.estrategia_id = e.id 
    ORDER BY o.fecha_creacion DESC";

    $result = $conexion->query($sql);

    if (!$result) {
        throw new Exception("Error en la consulta: " . $conexion->error);
    }

    $optimizaciones = array();

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $optimizaciones[] = array(
                'id' => $row['id'],
                'cliente_id' => $row['cliente_id'],
                'estrategia_id' => $row['estrategia_id'],
                'cliente_nombre' => $row['cliente_nombre'] ?: $row['cliente_nombre_db'],
                'estrategia_nombre' => $row['estrategia_nombre'] ?: $row['estrategia_nombre_db'],
                'fecha_inicio_analisis' => $row['fecha_inicio_analisis'],
                'fecha_fin_analisis' => $row['fecha_fin_analisis'],
                'funciono_bien' => $row['funciono_bien'],
                'no_funciono' => $row['no_funciono'],
                'propuestas_mejora' => $row['propuestas_mejora'],
                'impacto_esperado' => $row['impacto_esperado'],
                'prioridad' => $row['prioridad'],
                'metrica_principal' => $row['metrica_principal'],
                'responsable_implementacion' => $row['responsable_implementacion'],
                'fecha_limite' => $row['fecha_limite'],
                'presupuesto_adicional' => $row['presupuesto_adicional'],
                'observaciones' => $row['observaciones'],
                'fecha_creacion' => $row['fecha_creacion']
            );
        }
    }

    // Devolver JSON
    echo json_encode($optimizaciones);

} catch (Exception $e) {
    // En caso de error, devolver un JSON con el error
    http_response_code(500);
    echo json_encode([
        'error' => true,
        'message' => $e->getMessage(),
        'data' => []
    ]);
} finally {
    if (isset($conexion)) {
        $conexion->close();
    }
}
?>