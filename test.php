<?php
include 'config.php';

echo "<h2>Test de Optimizaciones</h2>";

// Verificar conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

echo "<p style='color: green;'>✓ Conexión a la base de datos exitosa</p>";

// Verificar si la tabla optimizaciones existe
$sql_check_table = "SHOW TABLES LIKE 'optimizaciones'";
$result = $conexion->query($sql_check_table);

if ($result->num_rows > 0) {
    echo "<p style='color: green;'>✓ La tabla 'optimizaciones' existe</p>";
    
    // Contar registros
    $sql_count = "SELECT COUNT(*) as total FROM optimizaciones";
    $result_count = $conexion->query($sql_count);
    $row_count = $result_count->fetch_assoc();
    
    echo "<p><strong>Total de optimizaciones:</strong> " . $row_count['total'] . "</p>";
    
    if ($row_count['total'] > 0) {
        // Mostrar estructura de la tabla
        $sql_describe = "DESCRIBE optimizaciones";
        $result_describe = $conexion->query($sql_describe);
        
        echo "<h3>Estructura de la tabla:</h3>";
        echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
        echo "<tr><th>Campo</th><th>Tipo</th><th>Nulo</th><th>Clave</th></tr>";
        
        while ($row = $result_describe->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['Field'] . "</td>";
            echo "<td>" . $row['Type'] . "</td>";
            echo "<td>" . $row['Null'] . "</td>";
            echo "<td>" . $row['Key'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        // Mostrar algunos registros de ejemplo
        $sql_sample = "SELECT 
            id, 
            cliente_nombre, 
            estrategia_nombre, 
            prioridad, 
            impacto_esperado, 
            fecha_creacion 
        FROM optimizaciones 
        ORDER BY fecha_creacion DESC 
        LIMIT 5";
        
        $result_sample = $conexion->query($sql_sample);
        
        echo "<h3>Últimas 5 optimizaciones:</h3>";
        echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
        echo "<tr><th>ID</th><th>Cliente</th><th>Estrategia</th><th>Prioridad</th><th>Impacto</th><th>Fecha</th></tr>";
        
        while ($row = $result_sample->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . ($row['cliente_nombre'] ?: 'Sin cliente') . "</td>";
            echo "<td>" . ($row['estrategia_nombre'] ?: 'Sin estrategia') . "</td>";
            echo "<td>" . ($row['prioridad'] ?: 'Sin prioridad') . "</td>";
            echo "<td>" . ($row['impacto_esperado'] ?: 'Sin impacto') . "</td>";
            echo "<td>" . $row['fecha_creacion'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        
    } else {
        echo "<p style='color: orange;'>⚠ La tabla existe pero no tiene registros</p>";
        echo "<p>Para probar, crea una optimización desde: <a href='optimizacion.html'>optimizacion.html</a></p>";
    }
    
} else {
    echo "<p style='color: red;'>✗ La tabla 'optimizaciones' NO existe</p>";
    echo "<p>Necesitas crearla con este SQL:</p>";
    echo "<pre>";
    echo "CREATE TABLE IF NOT EXISTS optimizaciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT NOT NULL,
    estrategia_id INT NOT NULL,
    cliente_nombre VARCHAR(255),
    estrategia_nombre VARCHAR(255),
    fecha_inicio_analisis DATE NOT NULL,
    fecha_fin_analisis DATE NOT NULL,
    funciono_bien TEXT NOT NULL,
    no_funciono TEXT NOT NULL,
    propuestas_mejora TEXT NOT NULL,
    impacto_esperado VARCHAR(100) NOT NULL,
    prioridad ENUM('Urgente', 'Alta', 'Media', 'Baja') NOT NULL,
    metrica_principal VARCHAR(100) NOT NULL,
    responsable_implementacion VARCHAR(255) NOT NULL,
    fecha_limite DATE NOT NULL,
    presupuesto_adicional DECIMAL(15,2) DEFAULT 0,
    observaciones TEXT,
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id),
    FOREIGN KEY (estrategia_id) REFERENCES estrategias(id)
);";
    echo "</pre>";
}

// Verificar otras tablas relacionadas
echo "<h3>Verificación de tablas relacionadas:</h3>";

$tablas = ['clientes', 'estrategias'];
foreach ($tablas as $tabla) {
    $sql_check = "SHOW TABLES LIKE '$tabla'";
    $result_check = $conexion->query($sql_check);
    
    if ($result_check->num_rows > 0) {
        $sql_count_tabla = "SELECT COUNT(*) as total FROM $tabla";
        $result_count_tabla = $conexion->query($sql_count_tabla);
        $row_count_tabla = $result_count_tabla->fetch_assoc();
        
        echo "<p style='color: green;'>✓ Tabla '$tabla': " . $row_count_tabla['total'] . " registros</p>";
    } else {
        echo "<p style='color: red;'>✗ Tabla '$tabla' no existe</p>";
    }
}

$conexion->close();

echo "<hr>";
echo "<p><a href='obtener_optimizaciones.php'>Probar obtener_optimizaciones.php</a></p>";
echo "<p><a href='ver_optimizaciones.html'>Ir a ver_optimizaciones.html</a></p>";
echo "<p><a href='optimizacion.html'>Crear nueva optimización</a></p>";
?>