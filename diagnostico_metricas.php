<?php
// Habilitar reporte de errores para diagnóstico
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);

header('Content-Type: application/json');

try {
    echo json_encode([
        'step' => 'inicio',
        'message' => 'Iniciando diagnóstico...',
        'php_version' => phpversion(),
        'post_data' => $_POST,
        'files_data' => $_FILES
    ]);
    
    // Verificar si existe config.php
    if (!file_exists('config.php')) {
        throw new Exception("Archivo config.php no encontrado");
    }
    
    include 'config.php';
    
    // Verificar conexión
    if ($conexion->connect_error) {
        throw new Exception("Error de conexión: " . $conexion->connect_error);
    }
    
    // Verificar si se subió archivo
    if (!isset($_FILES['csv_file'])) {
        throw new Exception("No se recibió archivo CSV");
    }
    
    if ($_FILES['csv_file']['error'] !== UPLOAD_ERR_OK) {
        $error_messages = [
            UPLOAD_ERR_INI_SIZE => 'El archivo excede upload_max_filesize',
            UPLOAD_ERR_FORM_SIZE => 'El archivo excede MAX_FILE_SIZE',
            UPLOAD_ERR_PARTIAL => 'El archivo se subió parcialmente',
            UPLOAD_ERR_NO_FILE => 'No se subió archivo',
            UPLOAD_ERR_NO_TMP_DIR => 'Falta directorio temporal',
            UPLOAD_ERR_CANT_WRITE => 'Error al escribir en disco',
            UPLOAD_ERR_EXTENSION => 'Extensión de PHP detuvo la subida'
        ];
        
        $error_code = $_FILES['csv_file']['error'];
        $error_msg = $error_messages[$error_code] ?? "Error desconocido: $error_code";
        throw new Exception("Error de archivo: $error_msg");
    }
    
    // Verificar campos requeridos
    $required_fields = ['cliente_id', 'estrategia_id', 'cliente_nombre', 'estrategia_nombre'];
    foreach ($required_fields as $field) {
        if (!isset($_POST[$field]) || empty($_POST[$field])) {
            throw new Exception("Campo requerido faltante: $field");
        }
    }
    
    // Verificar estructura de tabla
    $result = $conexion->query("DESCRIBE metricas");
    if (!$result) {
        throw new Exception("No se puede acceder a la tabla metricas: " . $conexion->error);
    }
    
    $columns = [];
    while ($row = $result->fetch_assoc()) {
        $columns[] = $row['Field'];
    }
    
    // Verificar directorio uploads
    $upload_dir = 'uploads/csv/';
    if (!is_dir($upload_dir)) {
        if (!mkdir($upload_dir, 0777, true)) {
            throw new Exception("No se puede crear directorio: $upload_dir");
        }
    }
    
    if (!is_writable($upload_dir)) {
        throw new Exception("Directorio no escribible: $upload_dir");
    }
    
    // Todo está bien
    echo json_encode([
        'success' => true,
        'message' => 'Diagnóstico completado exitosamente',
        'data' => [
            'conexion' => 'OK',
            'archivo_recibido' => $_FILES['csv_file']['name'],
            'archivo_size' => $_FILES['csv_file']['size'],
            'campos_post' => array_keys($_POST),
            'columnas_tabla' => $columns,
            'directorio_uploads' => $upload_dir,
            'directorio_escribible' => is_writable($upload_dir)
        ]
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'error_line' => $e->getLine(),
        'error_file' => $e->getFile(),
        'trace' => $e->getTraceAsString()
    ]);
}
?>