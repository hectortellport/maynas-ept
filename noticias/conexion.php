<?php
$host = 'localhost';
$dbname = 'DBMAYNAS2025';
$username = 'root'; // Cambiar según tu configuración
$password = ''; // Cambiar según tu configuración

try {
    $conexion = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>