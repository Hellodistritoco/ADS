<?php
include 'config.php';

// Configurar header para JSON
header('Content-Type: application/json');

try {
    // Verificar conexión
    if ($conexion->connect_error) {
        throw new Exception("Error de conexión: " . $conexion->connect_error);
    }

    // Verificar que se recibió el ID de la optimización
    if (!isset($_POST['optimizacion_id']) || empty($_POST['optimizacion_id'])) {
        throw new Exception("ID de optimización requerido");
    }

    $optimizacion_id = intval($_POST['optimizacion_id']);

    // Actualizar el estado de la optimización a implementada
    $sql = "UPDATE optimizaciones SET 
            estado = 'implementada',
            fecha_implementacion = NOW()
            WHERE id = ?";

    $stmt = $conexion->prepare($sql);
    
    if (!$stmt) {
        throw new Exception("Error preparando consulta: " . $conexion->error);
    }

    $stmt->bind_param("i", $optimizacion_id);
    
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo json_encode([
                'success' => true,
                'message' => 'Optimización marcada como implementada exitosamente'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'No se encontró la optimización o ya estaba implementada'
            ]);
        }
    } else {
        throw new Exception("Error ejecutando consulta: " . $stmt->error);
    }

    $stmt->close();

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
} finally {
    if (isset($conexion)) {
        $conexion->close();
    }
}
?>