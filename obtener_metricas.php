<?php
include 'config.php';

// Configurar header para JSON
header('Content-Type: application/json');

// Consultar todas las métricas con las nuevas columnas
$sql = "SELECT 
    m.*,
    c.nombre_cliente as cliente_nombre_db,
    e.nombre_estrategia as estrategia_nombre_db
FROM metricas m 
LEFT JOIN clientes c ON m.cliente_id = c.id 
LEFT JOIN estrategias e ON m.estrategia_id = e.id 
ORDER BY m.fecha_creacion DESC";

$result = $conexion->query($sql);

$metricas = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $metricas[] = array(
            'id' => $row['id'],
            'cliente_id' => $row['cliente_id'],
            'estrategia_id' => $row['estrategia_id'],
            'cliente_nombre' => $row['cliente_nombre'] ?: $row['cliente_nombre_db'],
            'estrategia_nombre' => $row['estrategia_nombre'] ?: $row['estrategia_nombre_db'],
            'tipo_estrategia' => $row['tipo_estrategia'],
            'plataforma' => $row['plataforma'],
            'etapa_funnel' => $row['etapa_funnel'], // NUEVO
            'tipo_metrica' => $row['tipo_metrica'], // NUEVO
            'fecha_inicio' => $row['fecha_inicio'],
            'fecha_fin' => $row['fecha_fin'],
            'descripcion' => $row['descripcion'],
            'archivo_original' => $row['archivo_original'],
            'archivo_guardado' => $row['archivo_guardado'],
            'ruta_archivo' => $row['ruta_archivo'],
            'csv_headers' => $row['csv_headers'],
            'total_filas' => $row['total_filas'],
            'fecha_creacion' => $row['fecha_creacion']
        );
    }
}

// Devolver JSON
echo json_encode($metricas);

$conexion->close();
?>