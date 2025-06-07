<?php
// Test ultra simple para debug
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

try {
    // Log básico
    error_log("=== TEST UPLOAD INICIADO ===");
    error_log("POST data: " . print_r($_POST, true));
    error_log("FILES data: " . print_r($_FILES, true));
    
    $response = [
        'success' => true,
        'message' => 'Test completado',
        'debug' => [
            'php_version' => phpversion(),
            'post_count' => count($_POST),
            'files_count' => count($_FILES),
            'server_time' => date('Y-m-d H:i:s'),
            'memory_usage' => memory_get_usage(true),
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'post_max_size' => ini_get('post_max_size'),
            'max_execution_time' => ini_get('max_execution_time')
        ],
        'received_data' => [
            'post_keys' => array_keys($_POST),
            'file_keys' => array_keys($_FILES),
            'cliente_id' => $_POST['cliente_id'] ?? 'NO_RECIBIDO',
            'estrategia_id' => $_POST['estrategia_id'] ?? 'NO_RECIBIDO',
            'csv_file_name' => $_FILES['csv_file']['name'] ?? 'NO_ARCHIVO',
            'csv_file_size' => $_FILES['csv_file']['size'] ?? 0,
            'csv_file_error' => $_FILES['csv_file']['error'] ?? 'NO_ERROR_INFO'
        ]
    ];
    
    // Verificar archivo
    if (isset($_FILES['csv_file']) && $_FILES['csv_file']['error'] === UPLOAD_ERR_OK) {
        $response['file_info'] = [
            'name' => $_FILES['csv_file']['name'],
            'size' => $_FILES['csv_file']['size'],
            'type' => $_FILES['csv_file']['type'],
            'tmp_name' => $_FILES['csv_file']['tmp_name'],
            'error' => $_FILES['csv_file']['error']
        ];
        
        // Intentar leer el archivo
        $content = file_get_contents($_FILES['csv_file']['tmp_name']);
        $response['file_content_length'] = strlen($content);
        $response['file_first_100_chars'] = substr($content, 0, 100);
    } else {
        $response['file_error'] = 'No se recibió archivo o hubo error';
        if (isset($_FILES['csv_file']['error'])) {
            $errors = [
                UPLOAD_ERR_INI_SIZE => 'Archivo muy grande (upload_max_filesize)',
                UPLOAD_ERR_FORM_SIZE => 'Archivo muy grande (MAX_FILE_SIZE)',
                UPLOAD_ERR_PARTIAL => 'Archivo subido parcialmente',
                UPLOAD_ERR_NO_FILE => 'No se subió archivo',
                UPLOAD_ERR_NO_TMP_DIR => 'Falta directorio temporal',
                UPLOAD_ERR_CANT_WRITE => 'Error escribiendo en disco',
                UPLOAD_ERR_EXTENSION => 'Extensión PHP detuvo subida'
            ];
            $response['upload_error_detail'] = $errors[$_FILES['csv_file']['error']] ?? 'Error desconocido';
        }
    }
    
    error_log("Response: " . json_encode($response));
    echo json_encode($response, JSON_PRETTY_PRINT);
    
} catch (Exception $e) {
    error_log("ERROR: " . $e->getMessage());
    
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage(),
        'line' => $e->getLine(),
        'file' => basename($e->getFile()),
        'trace' => $e->getTraceAsString()
    ], JSON_PRETTY_PRINT);
}
?>