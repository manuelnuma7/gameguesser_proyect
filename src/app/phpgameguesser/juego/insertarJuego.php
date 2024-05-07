<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: POST");
header('Content-Type: application/json');

// Función para normalizar el nombre quitando espacios y cambiando a minúsculas
function normalizarNombre($nombre) {
    return strtolower(str_replace(' ', '', $nombre));
}

// Obtener datos JSON desde la solicitud
$json = file_get_contents('php://input');
$params = json_decode($json);

// Valores principales para insertar en biblioteca_juego
$nombre = $params->nombre;
$nombreNormalizado = normalizarNombre($nombre);
$fecha = $params->fecha;
$nota = $params->nota;
$companion = $params->companion;
$nivel = $params->nivel;

// Listas de géneros y plataformas
$generos = $params->generos;
$plataformas = $params->plataformas;

// Listas opcionales de imágenes y música
$imagenes = isset($params->imagenes) ? $params->imagenes : [];
$musica = isset($params->musica) ? $params->musica : [];

try {
    $mbd = new PDO('mysql:host=localhost;dbname=gameguesser', 'root', '');
    $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Comprobar si el juego ya existe
    $sqlComprobar = "SELECT COUNT(*) FROM biblioteca_juego WHERE REPLACE(LOWER(nombre), ' ', '') = :nombre";
    $stmtComprobar = $mbd->prepare($sqlComprobar);
    $stmtComprobar->execute([':nombre' => $nombreNormalizado]);
    $existe = $stmtComprobar->fetchColumn();

    // Si el juego ya existe, devolver error
    if ($existe > 0) {
        echo json_encode(['error' => 'Este juego ya existe en la base de datos.']);
        exit; // Termina la ejecución
    }

    // Empezar la transacción para insertar el nuevo juego
    $mbd->beginTransaction();

    // Insertar el juego en biblioteca_juego
    $sqlJuego = "INSERT INTO biblioteca_juego (nombre, fecha, nota, companion, nivel) VALUES (:nombre, :fecha, :nota, :companion, :nivel)";
    $stmtJuego = $mbd->prepare($sqlJuego);
    $stmtJuego->execute([
        ':nombre' => $nombre,
        ':fecha' => $fecha,
        ':nota' => $nota,
        ':companion' => $companion,
        ':nivel' => $nivel
    ]);
    $idJuego = $mbd->lastInsertId(); // Obtener el ID recién insertado del juego

    // Insertar géneros relacionados en genero_juego
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
                ':id_biblioteca_juego' => $idJuego
            ]);
        }
    }

    // Insertar plataformas relacionadas en plataforma_juego
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
                ':id_biblioteca_juego' => $idJuego
            ]);
        }
    }

    // Insertar imágenes relacionadas en la tabla imagen
    foreach ($imagenes as $nombreImagen) {
        $sqlImagen = "INSERT INTO imagen (id_biblioteca_juego, nombre) VALUES (:id_biblioteca_juego, :nombre)";
        $stmtImagen = $mbd->prepare($sqlImagen);
        $stmtImagen->execute([
            ':id_biblioteca_juego' => $idJuego,
            ':nombre' => $nombreImagen
        ]);
    }

    // Insertar música relacionada en la tabla musica
    foreach ($musica as $nombreMusica) {
        $sqlMusica = "INSERT INTO musica (id_biblioteca_juego, nombre) VALUES (:id_biblioteca_juego, :nombre)";
        $stmtMusica = $mbd->prepare($sqlMusica);
        $stmtMusica->execute([
            ':id_biblioteca_juego' => $idJuego,
            ':nombre' => $nombreMusica
        ]);
    }

    $mbd->commit(); // Confirmar todas las operaciones
    echo json_encode(['success' => 'Juego insertado correctamente con todas sus relaciones']);
} catch (PDOException $e) {
    $mbd->rollBack(); // Revertir transacción en caso de error
    echo json_encode(['error' => ['msg' => $e->getMessage(), 'code' => $e->getCode()]]);
}
