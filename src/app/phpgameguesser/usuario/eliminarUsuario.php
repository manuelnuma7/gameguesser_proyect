<?php 
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");

// Leer el ID desde la URL
$id = $_GET['id'];

if ($id) {
    try {
        // Crear conexión a la base de datos
        $mbd = new PDO('mysql:host=localhost;dbname=gameguesser', "root", "");

        // Inicia una transacción para manejar las operaciones dependientes
        $mbd->beginTransaction();

        // Primero elimina las entradas dependientes de la tabla 'ranking'
        $sentenciaRanking = $mbd->prepare("DELETE FROM ranking WHERE id = :id");
        $sentenciaRanking->bindParam(':id', $id, PDO::PARAM_INT);
        $sentenciaRanking->execute();

        // Luego elimina el usuario
        $sentenciaUsuario = $mbd->prepare("DELETE FROM usuario WHERE id = :id");
        $sentenciaUsuario->bindParam(':id', $id, PDO::PARAM_INT);
        $sentenciaUsuario->execute();

        // Verificar que la sentencia fue exitosa
        if ($sentenciaUsuario->rowCount() > 0) {
            // Commit las transacciones
            $mbd->commit();
            echo json_encode(array('success' => 'Usuario y registros relacionados eliminados correctamente'));
        } else {
            $mbd->rollBack(); // Revierte la transacción si no se eliminó el usuario
            echo json_encode(array('error' => 'No se encontró el usuario o no pudo ser eliminado'));
        }

        // Cerrar conexión
        $mbd = null;
    } catch (PDOException $e) {
        $mbd->rollBack(); // Asegura revertir si ocurre un error
        header('Content-Type: application/json');
        echo json_encode(array(
            'error' => array(
                'msg' => $e->getMessage(),
                'code' => $e->getCode()
            )
        ));
    }
} else {
    echo json_encode(array('error' => 'ID inválido o no proporcionado'));
}
?>
