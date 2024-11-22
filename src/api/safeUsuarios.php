<?php
include '../config/db_connect.php';
session_start();

if (!isset($_SESSION['email'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Acceso denegado']);
    exit();
}

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        $email = $_SESSION['email'];
        $sql = "SELECT email, telefono, cuidad, pais, numero_tarjeta, nombre_tarjeta FROM usuario WHERE email = '$email'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $usuario = $result->fetch_assoc();
            echo json_encode(['usuario' => $usuario]);
        } else {
            echo json_encode(['error' => 'Usuario no encontrado']);
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($data['telefono'], $data['cuidad'], $data['pais'])) {
            echo json_encode(['error' => 'Faltan campos para actualizar']);
            exit();
        }

        $email = $_SESSION['email'];
        $telefono = $data['telefono'];
        $cuidad = $data['cuidad'];
        $pais = $data['pais'];
        $numero_tarjeta = isset($data['numero_tarjeta']) ? $data['numero_tarjeta'] : null;
        $codigo_verificador = isset($data['codigo_verificador']) ? $data['codigo_verificador'] : null;
        $nombre_tarjeta = isset($data['nombre_tarjeta']) ? $data['nombre_tarjeta'] : null;

        $sql = "UPDATE usuario SET telefono = '$telefono', cuidad = '$cuidad', pais = '$pais', 
                numero_tarjeta = '$numero_tarjeta', codigo_verificador = '$codigo_verificador', 
                nombre_tarjeta = '$nombre_tarjeta' WHERE email = '$email'";

        if ($conn->query($sql)) {
            echo json_encode(['success' => 'Datos actualizados correctamente']);
        } else {
            echo json_encode(['error' => 'Error al actualizar los datos']);
        }
        break;

    default:
        echo json_encode(['error' => 'Método no permitido']);
        break;
}
