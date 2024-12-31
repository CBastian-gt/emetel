<?php
$host = "localhost";
$dbname = "vehiculos"; // Reemplaza con el nombre de tu base de datos
$user = "root";
$password = ""; // Reemplaza con tu contraseña

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage()); // Detiene la ejecución si hay un error de conexión
}
?>