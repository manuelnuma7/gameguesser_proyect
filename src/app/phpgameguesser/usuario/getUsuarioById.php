<?php 
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");

$id = $_GET['id'];
try {    
    $mbd = new PDO('mysql:host=localhost;dbname=gameguesser', "root", "");
    $sentencia = $mbd->prepare("SELECT * FROM usuario WHERE id=:id");
    $sentencia->bindParam(':id', $id);
    $sentencia->execute();
    $usuario = $sentencia->fetch(PDO::FETCH_ASSOC); // Cambia fetchAll a fetch
    header('Content-Type: application/json');
    if ($usuario) {
        echo json_encode($usuario); // Devuelve el objeto usuario directamente
    } else {
        echo json_encode(array('error' => 'No se encontró el usuario con el ID proporcionado'));
    }
    $mbd = null;
} catch (PDOException $e) {
    header('Content-Type: application/json');
    echo json_encode(array(
        'error' => array(
            'msg' => $e->getMessage(),
            'code' => $e->getCode()
        )
    ));
}
?>
