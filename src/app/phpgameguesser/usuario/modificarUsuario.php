<?php 
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");

$json = file_get_contents('php://input');
$params = json_decode($json);

$id = $params->id;
$nombre = $params->nombre;
$email = $params->email;
$clave = $params->clave;
$admin = $params->admin;

try {  
    $mbd = new PDO('mysql:host=localhost;dbname=gameguesser', "root", "");
    $sentencia = $mbd->prepare("UPDATE usuario SET nombre = :nombre, email = :email, clave = :clave, admin = :admin WHERE id = :id");
    
    $sentencia->bindParam(':id', $id);
    $sentencia->bindParam(':nombre', $nombre);
    $sentencia->bindParam(':email', $email);
    $sentencia->bindParam(':clave', $clave);
    $sentencia->bindParam(':admin', $admin);

    $sentencia->execute();
    $error = $sentencia->errorInfo();

    header('Content-Type: application/json');
    if ($sentencia->errorCode() == "00000") {
        echo json_encode(array('success' => 'Usuario actualizado correctamente'));
    } else {
        echo json_encode(array(
            'error' => array(
                'sqlstate' => current($error),
                'code' => next($error),
                'msg' => next($error)
            )
        ));
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
