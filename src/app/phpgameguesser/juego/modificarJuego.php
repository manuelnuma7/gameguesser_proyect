<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: POST");
header('Content-Type: application/json');

// Obtener datos JSON desde la solicitud
$json = file_get_contents('php://input');
$params = json_decode($json);

$id = $params->id;
$nombre = $params->nombre;
$fecha = $params->fecha;
$nota = $params->nota;
$companion = $params->companion;
$nivel = $params->nivel;
$generos = $params->generos;
$plataformas = $params->plataformas;
$imagenes = $params->imagenes;
$musica = $params->musica;

try {
    $mbd = new PDO('mysql:host=localhost;dbname=gameguesser', 'root', '');
    $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Empezar la transacción
    $mbd->beginTransaction();

    // Actualizar el juego en biblioteca_juego
    $sqlJuego = "UPDATE biblioteca_juego SET nombre = :nombre, fecha = :fecha, nota = :nota, companion = :companion, nivel = :nivel WHERE id_biblioteca_juego = :id";
    $stmtJuego = $mbd->prepare($sqlJuego);
    $stmtJuego->execute([
        ':nombre' => $nombre,
        ':fecha' => $fecha,
        ':nota' => $nota,
        ':companion' => $companion,
        ':nivel' => $nivel,
        ':id' => $id
    ]);

    // Actualizar géneros relacionados en genero_juego
    $sqlDeleteGeneros = "DELETE FROM genero_juego WHERE id_biblioteca_juego = :id";
    $stmtDeleteGeneros = $mbd->prepare($sqlDeleteGeneros);
    $stmtDeleteGeneros->execute([':id' => $id]);

    foreach ($generos as $nombreGenero) {
        $sqlBuscarGenero = "SELECT id_genero FROM genero WHERE genero = :nombreGenero";
        $stmtBuscarGenero = $mbd->prepare($sqlBuscarGenero);
        $stmtBuscarGenero->execute([':nombreGenero' => $nombreGenero]);
        $idGenero = $stmtBuscarGenero->fetchColumn();

        if ($idGenero) {
            $sqlGenero = "INSERT INTO genero_juego (id_genero, id_biblioteca_juego) VALUES (:id_genero, :id_biblioteca_juego)";
            $stmtGenero = $mbd->prepare($sqlGenero);
            $stmtGenero->execute([
                ':id_genero' => $idGenero,
                ':id_biblioteca_juego' => $id
            ]);
        }
    }

    // Actualizar plataformas relacionadas en plataforma_juego
    $sqlDeletePlataformas = "DELETE FROM plataforma_juego WHERE id_biblioteca_juego = :id";
    $stmtDeletePlataformas = $mbd->prepare($sqlDeletePlataformas);
    $stmtDeletePlataformas->execute([':id' => $id]);

    foreach ($plataformas as $nombrePlataforma) {
        $sqlBuscarPlataforma = "SELECT id_plataforma FROM plataforma WHERE nombre = :nombrePlataforma";
        $stmtBuscarPlataforma = $mbd->prepare($sqlBuscarPlataforma);
        $stmtBuscarPlataforma->execute([':nombrePlataforma' => $nombrePlataforma]);
        $idPlataforma = $stmtBuscarPlataforma->fetchColumn();

        if ($idPlataforma) {
            $sqlPlataforma = "INSERT INTO plataforma_juego (id_plataforma, id_biblioteca_juego) VALUES (:id_plataforma, :id_biblioteca_juego)";
            $stmtPlataforma = $mbd->prepare($sqlPlataforma);
            $stmtPlataforma->execute([
                ':id_plataforma' => $idPlataforma,
                ':id_biblioteca_juego' => $id
            ]);
        }
    }

    // Actualizar imágenes relacionadas en la tabla imagen
    $sqlDeleteImagenes = "DELETE FROM imagen WHERE id_biblioteca_juego = :id";
    $stmtDeleteImagenes = $mbd->prepare($sqlDeleteImagenes);
    $stmtDeleteImagenes->execute([':id' => $id]);

    foreach ($imagenes as $nombreImagen) {
        $sqlImagen = "INSERT INTO imagen (id_biblioteca_juego, nombre) VALUES (:id_biblioteca_juego, :nombre)";
        $stmtImagen = $mbd->prepare($sqlImagen);
        $stmtImagen->execute([
            ':id_biblioteca_juego' => $id,
            ':nombre' => $nombreImagen
        ]);
    }

    // Actualizar música relacionada en la tabla musica
    $sqlDeleteMusica = "DELETE FROM musica WHERE id_biblioteca_juego = :id";
    $stmtDeleteMusica = $mbd->prepare($sqlDeleteMusica);
    $stmtDeleteMusica->execute([':id' => $id]);

    foreach ($musica as $nombreMusica) {
        $sqlMusica = "INSERT INTO musica (id_biblioteca_juego, nombre) VALUES (:id_biblioteca_juego, :nombre)";
        $stmtMusica = $mbd->prepare($sqlMusica);
        $stmtMusica->execute([
            ':id_biblioteca_juego' => $id,
            ':nombre' => $nombreMusica
        ]);
    }

    $mbd->commit(); // Confirmar todas las operaciones
    echo json_encode(['success' => 'Juego modificado correctamente con todas sus relaciones']);
} catch (PDOException $e) {
    $mbd->rollBack(); // Revertir transacción en caso de error
    echo json_encode(['error' => ['msg' => $e->getMessage(), 'code' => $e->getCode()]]);
}
?>
