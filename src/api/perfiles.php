<?php
// api/perfiles.php
include '../../config/db_connect.php';
session_start();

if (!isset($_SESSION['email']) || $_SESSION['email'] !== 'admin@swagplay.com') {
    http_response_code(403);
    exit();
}

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        $sql = "SELECT id_perfil, nombre, email FROM perfiles";
        $result = $conn->query($sql);
        $perfiles = [];
        while ($row = $result->fetch_assoc()) {
            $perfiles[] = $row;
        }
        echo json_encode(['perfiles' => $perfiles]);
        break;

    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        $sql = "INSERT INTO perfiles (id_perfil, nombre, email) 
                VALUES ('$data[id_perfil]', '$data[nombre]', '$data[email]')";
        echo json_encode(['success' => $conn->query($sql)]);
        break;

    case 'PUT':
        $data = json_decode(file_get_contents('php://input'), true);
        $sql = "UPDATE perfiles SET nombre = '$data[nombre]' WHERE id_perfil = '$data[id_perfil]'";
        echo json_encode(['success' => $conn->query($sql)]);
        break;

    case 'DELETE':
        $id_perfil = $_GET['id_perfil'];
        $sql = "DELETE FROM perfiles WHERE id_perfil = '$id_perfil'";
        echo json_encode(['success' => $conn->query($sql)]);
        break;
}
?>
