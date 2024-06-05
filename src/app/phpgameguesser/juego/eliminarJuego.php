<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: DELETE");
header('Content-Type: application/json');

$id = $_GET['id'] ?? null;

if (is_null($id)) {
    echo json_encode(['error' => 'El ID del juego es requerido']);
    exit;
}

try {
    $mbd = new PDO('mysql:host=localhost;dbname=gameguesser', 'root', '');
    $mbd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $mbd->beginTransaction();

    // Eliminar registros relacionados en otras tablas primero
    $sqlDeleteGeneros = "DELETE FROM genero_juego WHERE id_biblioteca_juego = :id";
    $stmtDeleteGeneros = $mbd->prepare($sqlDeleteGeneros);
    $stmtDeleteGeneros->execute([':id' => $id]);

    $sqlDeletePlataformas = "DELETE FROM plataforma_juego WHERE id_biblioteca_juego = :id";
    $stmtDeletePlataformas = $mbd->prepare($sqlDeletePlataformas);
    $stmtDeletePlataformas->execute([':id' => $id]);

    $sqlDeleteImagenes = "DELETE FROM imagen WHERE id_biblioteca_juego = :id";
    $stmtDeleteImagenes = $mbd->prepare($sqlDeleteImagenes);
    $stmtDeleteImagenes->execute([':id' => $id]);

    $sqlDeleteMusica = "DELETE FROM musica WHERE id_biblioteca_juego = :id";
    $stmtDeleteMusica = $mbd->prepare($sqlDeleteMusica);
    $stmtDeleteMusica->execute([':id' => $id]);

    $sqlDeleteJuego = "DELETE FROM biblioteca_juego WHERE id_biblioteca_juego = :id";
    $stmtDeleteJuego = $mbd->prepare($sqlDeleteJuego);
    $stmtDeleteJuego->execute([':id' => $id]);

    $mbd->commit();
    echo json_encode(['success' => 'Juego eliminado correctamente']);
} catch (PDOException $e) {
    $mbd->rollBack();
    echo json_encode(['error' => ['msg' => $e->getMessage(), 'code' => $e->getCode()]]);
}
?>
