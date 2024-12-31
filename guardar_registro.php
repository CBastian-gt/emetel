<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'conexion.php'; // Incluye el archivo de conexión

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $patente = $_POST["patente"];
    $chofer = $_POST["chofer"];
    $kilometraje = $_POST["kilometraje"];
    $tipo_movimiento = $_POST["tipo_movimiento"];

    // Validación de datos (más robusta)
    if (empty($patente) || strlen($patente) > 20 || empty($chofer) || strlen($chofer) > 255 || !is_numeric($kilometraje) || $kilometraje < 0) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Datos de entrada inválidos.']);
        exit;
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO registros (patente, chofer, kilometraje, tipo_movimiento, fecha, hora) VALUES (:patente, :chofer, :kilometraje, :tipo_movimiento, CURDATE(), CURTIME())");

        $stmt->execute([
            ':patente' => $patente,
            ':chofer' => $chofer,
            ':kilometraje' => $kilometraje,
            ':tipo_movimiento' => $tipo_movimiento
        ]);

        echo json_encode(['status' => 'success', 'message' => 'Registro guardado correctamente.']);
    } catch (PDOException $e) {
        http_response_code(500);
        error_log("Error en guardar_registro.php: " . $e->getMessage()); // Log del error
        echo json_encode(['status' => 'error', 'message' => 'Error al guardar el registro: ' . $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Método no permitido.']);
}
?>