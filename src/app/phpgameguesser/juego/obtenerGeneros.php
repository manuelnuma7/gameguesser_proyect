<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: GET");
header('Content-Type: application/json');

// Establecer conexión a la base de datos
try {
    $pdo = new PDO('mysql:host=localhost;dbname=gameguesser', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Realizar la consulta para obtener todos los géneros
    $stmt = $pdo->query("SELECT genero FROM genero");

    // Obtener todos los géneros como un array
    $generos = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // Devolver la lista en formato JSON
    echo json_encode($generos);

    // Cerrar la conexión
    $pdo = null;
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>