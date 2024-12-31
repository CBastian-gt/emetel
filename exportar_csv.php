<?php
require_once 'conexion.php';

// Cabeceras para la descarga (con Content-Type más específico y BOM UTF-8)
header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
header('Content-Disposition: attachment; filename=Registros kilometrajes ' . date('d-m-Y') . '.csv');

// Salida con BOM UTF-8 (esencial para la codificación)
$output = fopen('php://output', 'w');
fputs($output, "\xEF\xBB\xBF");

// Usar punto y coma como delimitador (solución principal al problema de Excel)
$delimitador = ';';

// Encabezados de columna
$cabecera = array('Patente', 'Chofer', 'Hora', 'Kilometraje', 'Fecha');
fputcsv($output, $cabecera, $delimitador);

$whereClause = "";
$params = [];

// Manejo de filtros (con sanitización y validación mejoradas)
if (isset($_GET['fecha_inicio'], $_GET['fecha_fin']) && !empty($_GET['fecha_inicio']) && !empty($_GET['fecha_fin'])) {
    $fecha_inicio = filter_input(INPUT_GET, 'fecha_inicio', FILTER_SANITIZE_STRING);
    $fecha_fin = filter_input(INPUT_GET, 'fecha_fin', FILTER_SANITIZE_STRING);

    $fecha_inicio_obj = DateTime::createFromFormat('Y-m-d', $fecha_inicio);
    $fecha_fin_obj = DateTime::createFromFormat('Y-m-d', $fecha_fin);

    if ($fecha_inicio_obj && $fecha_fin_obj && $fecha_inicio_obj <= $fecha_fin_obj) {
        $whereClause = "WHERE fecha BETWEEN :fecha_inicio AND :fecha_fin";
        $params[':fecha_inicio'] = $fecha_inicio;
        $params[':fecha_fin'] = $fecha_fin;
    } else {
        fputcsv($output, ['Error: Formato de fecha incorrecto o rango inválido (AAAA-MM-DD).'], $delimitador);
        fclose($output);
        $pdo = null;
        exit;
    }
} elseif (isset($_GET['patente']) && !empty($_GET['patente'])) {
    $patente = filter_input(INPUT_GET, 'patente', FILTER_SANITIZE_STRING);
    $whereClause = "WHERE patente LIKE :patente";
    $params[':patente'] = "%" . $patente . "%";
}

try {
    $sql = "SELECT patente, chofer, hora, kilometraje, fecha FROM registros " . $whereClause . " ORDER BY fecha DESC, hora DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Formateo de fecha (dentro del bucle y con manejo de errores)
        $fecha_formateada = "";
        if ($fila['fecha'] !== null && $fila['fecha'] !== '0000-00-00') {
            $fecha_objeto = DateTime::createFromFormat('Y-m-d', $fila['fecha']);
            $fecha_formateada = $fecha_objeto ? $fecha_objeto->format('d-m-Y') : "Fecha inválida";
        } else {
            $fecha_formateada = "Sin fecha";
        }

        // Crear el array para fputcsv (con la fecha formateada)
        $fila_csv = [
            $fila['patente'],
            $fila['chofer'],
            $fila['hora'],
            $fila['kilometraje'],
            $fecha_formateada
        ];

        fputcsv($output, $fila_csv, $delimitador); // Usar el delimitador definido
    }
} catch (PDOException $e) {
    error_log("Error en exportar_csv.php: " . $e->getMessage());
    fputcsv($output, ['Error al exportar los datos: ' . $e->getMessage()], $delimitador);
}

fclose($output);
$pdo = null;
exit;
?>