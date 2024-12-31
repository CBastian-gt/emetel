<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'conexion.php';

$whereClause = "";
$params = [];

if (isset($_GET['patente']) && !empty($_GET['patente'])) {
    $whereClause = "WHERE patente LIKE :patente";
    $params[':patente'] = "%" . $_GET['patente'] . "%";
}

$sql = "SELECT patente, chofer, hora, kilometraje, fecha FROM registros " . $whereClause . " ORDER BY fecha DESC, hora DESC";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $registros = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Formatear la fecha DESPUÉS de obtener los registros
    $registros_formateados = [];
    foreach ($registros as $registro) {
        // Manejo de fechas NULL y '0000-00-00' y fechas invalidas
        if ($registro['fecha'] !== null && $registro['fecha'] !== '0000-00-00') {
            $fecha_objeto = DateTime::createFromFormat('Y-m-d', $registro['fecha']);
            if ($fecha_objeto) {
                $registro_formateado = $registro;
                $registro_formateado['fecha'] = $fecha_objeto->format('d-m-Y');
                $registros_formateados[] = $registro_formateado;
            } else {
                $registro_formateado = $registro;
                $registro_formateado['fecha'] = "Fecha inválida";
                $registros_formateados[] = $registro_formateado;
            }

        } else {
            $registro_formateado = $registro;
            $registro_formateado['fecha'] = "Sin fecha";
            $registros_formateados[] = $registro_formateado;
        }
    }

    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($registros_formateados);

} catch (PDOException $e) {
    http_response_code(500);
    error_log("Error en obtener_registros.php: " . $e->getMessage());
    echo json_encode(['error' => 'Error al obtener registros: ' . $e->getMessage()]);
}

?>