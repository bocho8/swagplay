<?php
include '../../config/db_connect.php';
session_start();

if (!isset($_SESSION['email']) || $_SESSION['email'] !== 'admin@swagplay.com') {
    http_response_code(403);
    echo json_encode(['error' => 'Acceso denegado']);
    exit();
}

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        $sql = "SELECT email, telefono, cuidad, pais FROM usuario";
        $result = $conn->query($sql);
        $usuarios = [];
        while ($row = $result->fetch_assoc()) {
            $usuarios[] = $row;
        }
        echo json_encode(['usuarios' => $usuarios]);
        break;

    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        $sql = "INSERT INTO usuario (email, contrasena, telefono, cuidad, pais) 
                VALUES ('$data[email]', '" . password_hash($data['contrasena'], PASSWORD_DEFAULT) . "', '$data[telefono]', '$data[cuidad]', '$data[pais]')";
        echo json_encode(['success' => $conn->query($sql)]);
        break;

    case 'PUT':
        $data = json_decode(file_get_contents('php://input'), true);
        $sql = "UPDATE usuario SET telefono = '$data[telefono]', cuidad = '$data[cuidad]', pais = '$data[pais]' WHERE email = '$data[email]'";
        echo json_encode(['success' => $conn->query($sql)]);
        break;

    case 'DELETE':
        $email = $_GET['email'];
        // Primero eliminamos registros relacionados
        $conn->query("DELETE FROM visualiza WHERE email = '$email'");
        $conn->query("DELETE FROM suscripcion WHERE email = '$email'");
        $conn->query("DELETE FROM perfiles WHERE email = '$email'");
        // Finalmente eliminamos el usuario
        $sql = "DELETE FROM usuario WHERE email = '$email'";
        echo json_encode(['success' => $conn->query($sql)]);
        break;
}