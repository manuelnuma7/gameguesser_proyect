<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

$id = $_GET['id'];

try {
    $pdo = new PDO('mysql:host=localhost;dbname=gameguesser', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = 'SELECT bj.id_biblioteca_juego, bj.nombre, bj.fecha, bj.nota, bj.companion, bj.nivel, g.genero, p.nombre AS plataforma, m.nombre AS musica, i.nombre AS imagen
              FROM biblioteca_juego bj
              LEFT JOIN genero_juego gj ON bj.id_biblioteca_juego = gj.id_biblioteca_juego
              LEFT JOIN genero g ON gj.id_genero = g.id_genero
              LEFT JOIN plataforma_juego pj ON bj.id_biblioteca_juego = pj.id_biblioteca_juego
              LEFT JOIN plataforma p ON pj.id_plataforma = p.id_plataforma
              LEFT JOIN musica m ON bj.id_biblioteca_juego = m.id_biblioteca_juego
              LEFT JOIN imagen i ON bj.id_biblioteca_juego = i.id_biblioteca_juego
              WHERE bj.id_biblioteca_juego = :id';

    $stmt = $pdo->prepare($query);
    $stmt->execute([':id' => $id]);

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($rows) {
        $juego = array(
            'id' => $rows[0]['id_biblioteca_juego'],
            'nombre' => $rows[0]['nombre'],
            'fecha' => $rows[0]['fecha'],
            'nota' => $rows[0]['nota'],
            'companion' => $rows[0]['companion'],
            'nivel' => $rows[0]['nivel'],
            'generos' => [],
            'plataformas' => [],
            'musica' => [],
            'imagenes' => []
        );

        foreach ($rows as $row) {
            if (!in_array($row['genero'], $juego['generos'])) {
                $juego['generos'][] = $row['genero'];
            }
            if (!in_array($row['plataforma'], $juego['plataformas'])) {
                $juego['plataformas'][] = $row['plataforma'];
            }
            if (!in_array($row['musica'], $juego['musica'])) {
                $juego['musica'][] = $row['musica'];
            }
            if (!in_array($row['imagen'], $juego['imagenes'])) {
                $juego['imagenes'][] = $row['imagen'];
            }
        }

        echo json_encode($juego);
    } else {
        echo json_encode(['error' => 'Juego no encontrado']);
    }
} catch (PDOException $e) {
    echo json_encode(['error' => ['msg' => $e->getMessage(), 'code' => $e->getCode()]]);
}
?>
