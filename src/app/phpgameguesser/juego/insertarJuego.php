<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");

$json = file_get_contents('php://input');
$params = json_decode($json);

// Valores obligatorios
$nombre = $params->nombre;
$fecha = $params->fecha;
$nota = $params->nota;
$companion = $params->companion;
$nivel = $params->nivel;
$generos = $params->genero; // Esto debería ser un array
$plataformas = $params->plataforma; // Esto debería ser un array

// Valores opcionales
$imagenes = isset($params->imagen) ? $params->imagen : [];
$musica = isset($params->musica) ? $params->musica : [];

try {
    $mbd = new PDO('mysql:host=localhost;dbname=gameguesser', "root", "");
    $mbd->beginTransaction();

    // Insertar en biblioteca_juego
    $sql = "INSERT INTO biblioteca_juego (nombre, fecha, nota, companion, nivel) VALUES (:nombre, :fecha, :nota, :companion, :nivel)";
    $stmt = $mbd->prepare($sql);
    $stmt->execute([
        ':nombre' => $nombre,
        ':fecha' => $fecha,
        ':nota' => $nota,
        ':companion' => $companion,
        ':nivel' => $nivel
    ]);
    $juegoId = $mbd->lastInsertId(); // Obtener el ID del juego insertado


    // Insertar en genero_juego y plataforma_juego
    foreach ($generos as $genero) {

        //buscar el id de los generos

        $stmt = $mbd->prepare("INSERT INTO genero_juego (id_biblioteca_juego, id_genero) VALUES (:juego_id, :genero)");
        $stmt->execute([':juego_id' => $juegoId, ':genero' => $genero]);
    }

    foreach ($plataformas as $plataforma) {

        //buscar el id de las plataformas
        $stmt = $mbd->prepare("INSERT INTO plataforma_juego (id_biblioteca_juego, id_plataforma) VALUES (:juego_id, :plataforma)");
        $stmt->execute([':juego_id' => $juegoId, ':plataforma' => $plataforma]);
    }

    // Insertar en imagen y musica si existen
    foreach ($imagenes as $imagen) {
        $stmt = $mbd->prepare("INSERT INTO imagen (id_biblioteca_juego, imagen) VALUES (:juego_id, :imagen)");
        $stmt->execute([':juego_id' => $juegoId, ':imagen' => $imagen]);
    }

    foreach ($musica as $musicaItem) {
        $stmt = $mbd->prepare("INSERT INTO musica (id_biblioteca_juego, musica) VALUES (:juego_id, :musica)");
        $stmt->execute([':juego_id' => $juegoId, ':musica' => $musicaItem]);
    }

    $mbd->commit(); // Confirmar todas las operaciones
    echo json_encode(array('success' => 'Juego insertado correctamente con todos sus detalles'));
} catch (PDOException $e) {
    $mbd->rollBack(); // Revertir todas las operaciones en caso de error
    header('Content-Type: application/json');
    echo json_encode(array('error' => array('msg' => $e->getMessage(), 'code' => $e->getCode())));
}
?>
