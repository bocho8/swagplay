<?php
// api/categorias.php
include '../../config/db_connect.php';
session_start();

if (!isset($_SESSION['email']) || $_SESSION['email'] !== 'admin@swagplay.com') {
    http_response_code(403);
    exit();
}

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        $sql = "SELECT id_pelicula, categoria FROM categorias";
        $result = $conn->query($sql);
        $categorias = [];
        while ($row = $result->fetch_assoc()) {
            $categorias[] = $row;
        }
        echo json_encode(['categorias' => $categorias]);
        break;

    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        $sql = "INSERT INTO categorias (id_pelicula, categoria) 
                VALUES ('$data[id_pelicula]', '$data[categoria]')";
        echo json_encode(['success' => $conn->query($sql)]);
        break;

    case 'PUT':
        $data = json_decode(file_get_contents('php://input'), true);
        $sql = "UPDATE categorias SET categoria = '$data[categoria]' WHERE id_pelicula = '$data[id_pelicula]'";
        echo json_encode(['success' => $conn->query($sql)]);
        break;

    case 'DELETE':
        $id_pelicula = $_GET['id_pelicula'];
        $sql = "DELETE FROM categorias WHERE id_pelicula = '$id_pelicula'";
        echo json_encode(['success' => $conn->query($sql)]);
        break;
}
?>
