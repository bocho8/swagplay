<?php
include '../config/db_connect.php';
include 'verificar_sesion.php';
session_start();

if (!isset($_SESSION['email']) || $_SESSION['email'] !== 'admin@swagplay.com') {
    http_response_code(403);
    exit();
}

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        $sql = "SELECT pantallas_simultaneas, nombre, email FROM suscripcion";
        $result = $conn->query($sql);
        $suscripciones = [];
        while ($row = $result->fetch_assoc()) {
            $suscripciones[] = $row;
        }
        echo json_encode(['suscripciones' => $suscripciones]);
        break;

    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        $sql = "INSERT INTO suscripcion (pantallas_simultaneas, nombre, email) 
                VALUES ('$data[pantallas_simultaneas]', '$data[nombre]', '$data[email]')";
        echo json_encode(['success' => $conn->query($sql)]);
        break;

    case 'PUT':
        $data = json_decode(file_get_contents('php://input'), true);
        $sql = "UPDATE suscripcion SET pantallas_simultaneas = '$data[pantallas_simultaneas]' WHERE email = '$data[email]'";
        echo json_encode(['success' => $conn->query($sql)]);
        break;

    case 'DELETE':
        $email = $_GET['email'];
        $sql = "DELETE FROM suscripcion WHERE email = '$email'";
        echo json_encode(['success' => $conn->query($sql)]);
        break;
}
?>
