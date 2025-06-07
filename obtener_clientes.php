<?php
include 'config.php';

// Configurar header para JSON
header('Content-Type: application/json');

// Consultar todos los clientes
$sql = "SELECT id, fecha, nombre_cliente, presupuesto, objetivo, manejado_por FROM clientes ORDER BY fecha DESC, nombre_cliente ASC";
$result = $conexion->query($sql);

$clientes = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $clientes[] = array(
            'id' => $row['id'],
            'fecha' => $row['fecha'],
            'nombre_cliente' => $row['nombre_cliente'],
            'presupuesto' => $row['presupuesto'],
            'objetivo' => $row['objetivo'],
            'manejado_por' => $row['manejado_por']
        );
    }
}

// Devolver JSON
echo json_encode($clientes);

$conexion->close();
?>