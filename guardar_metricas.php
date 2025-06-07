<?php
include 'config.php';

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Verificar que se haya subido un archivo
if (!isset($_FILES['csv_file']) || $_FILES['csv_file']['error'] !== UPLOAD_ERR_OK) {
    die("Error: No se pudo subir el archivo CSV");
}

// Recibir datos del formulario
$cliente_id = $_POST['cliente_id'];
$estrategia_id = $_POST['estrategia_id'];
$cliente_nombre = $_POST['cliente_nombre'];
$estrategia_nombre = $_POST['estrategia_nombre'];
$tipo_estrategia = $_POST['tipo_estrategia'];
$plataforma = $_POST['plataforma'];
$fecha_inicio = $_POST['fecha_inicio'];
$fecha_fin = $_POST['fecha_fin'];
$descripcion = $_POST['descripcion'];
$csv_headers = json_decode($_POST['csv_headers'], true);
$total_rows = $_POST['total_rows'];

// Información del archivo
$archivo_original = $_FILES['csv_file']['name'];
$archivo_temporal = $_FILES['csv_file']['tmp_name'];

// Crear directorio para archivos CSV si no existe
$upload_dir = 'uploads/csv/';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// Generar nombre único para el archivo
$extension = pathinfo($archivo_original, PATHINFO_EXTENSION);
$archivo_guardado = 'metricas_' . $cliente_id . '_' . $estrategia_id . '_' . date('Y-m-d_H-i-s') . '.' . $extension;
$ruta_completa = $upload_dir . $archivo_guardado;

// Mover el archivo subido
if (!move_uploaded_file($archivo_temporal, $ruta_completa)) {
    die("Error: No se pudo guardar el archivo CSV");
}

// Leer y procesar el archivo CSV
$csv_content = file_get_contents($ruta_completa);
$lines = explode("\n", $csv_content);
$csv_data = [];

// Procesar cada línea del CSV
for ($i = 1; $i < count($lines); $i++) {
    $line = trim($lines[$i]);
    if (!empty($line)) {
        $row = str_getcsv($line);
        if (count($row) === count($csv_headers)) {
            $csv_data[] = $row;
        }
    }
}

// Preparar consulta para insertar el registro principal de métricas
$sql_metricas = "INSERT INTO metricas (
    cliente_id,
    estrategia_id,
    cliente_nombre,
    estrategia_nombre,
    tipo_estrategia,
    plataforma,
    fecha_inicio,
    fecha_fin,
    descripcion,
    archivo_original,
    archivo_guardado,
    ruta_archivo,
    csv_headers,
    total_filas,
    fecha_creacion
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

$stmt_metricas = $conexion->prepare($sql_metricas);
$csv_headers_json = json_encode($csv_headers);

$stmt_metricas->bind_param(
    "iissssssssssi",
    $cliente_id,
    $estrategia_id,
    $cliente_nombre,
    $estrategia_nombre,
    $tipo_estrategia,
    $plataforma,
    $fecha_inicio,
    $fecha_fin,
    $descripcion,
    $archivo_original,
    $archivo_guardado,
    $ruta_completa,
    $csv_headers_json,
    $total_rows
);

if (!$stmt_metricas->execute()) {
    die("Error al guardar métricas principales: " . $stmt_metricas->error);
}

// Obtener el ID de la métrica recién insertada
$metrica_id = $conexion->insert_id;

// Preparar consulta para insertar los datos del CSV
$placeholders = str_repeat('?,', count($csv_headers));
$placeholders = rtrim($placeholders, ',');

$sql_datos = "INSERT INTO metricas_datos (
    metrica_id,
    fila_numero,
    datos_json,
    " . implode(',', array_map(function($header) {
        return "`" . str_replace('`', '``', $header) . "`";
    }, $csv_headers)) . "
) VALUES (?, ?, ?, " . $placeholders . ")";

$stmt_datos = $conexion->prepare($sql_datos);

// Insertar cada fila de datos
foreach ($csv_data as $fila_numero => $row_data) {
    $datos_json = json_encode($row_data);
    
    // Crear array de parámetros
    $params = array_merge(
        [$metrica_id, $fila_numero + 1, $datos_json],
        $row_data
    );
    
    // Crear string de tipos
    $types = 'iis' . str_repeat('s', count($csv_headers));
    
    $stmt_datos->bind_param($types, ...$params);
    
    if (!$stmt_datos->execute()) {
        // Si hay error, registrarlo pero continuar
        error_log("Error al insertar fila " . ($fila_numero + 1) . ": " . $stmt_datos->error);
    }
}

$stmt_metricas->close();
$stmt_datos->close();
$conexion->close();

// Redirigir con éxito
header("Location: metricas.html?exito=1&cliente_id=$cliente_id&estrategia_id=$estrategia_id");
exit();
?>