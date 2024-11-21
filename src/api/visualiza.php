<?php
include '../config/db_connect.php';
include 'usuarioPermisos.php';
session_start();

if (!isset($_SESSION['email']) || $_SESSION['email'] !== 'admin@swagplay.com') {
    http_response_code(403);
    exit();
}

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        $sql = "SELECT email, id_pelicula, calificacion, segundo_pelicula FROM visualiza";
        $result = $conn->query($sql);
        $visualiza = [];
        while ($row = $result->fetch_assoc()) {
            $visualiza[] = $row;
        }
        echo json_encode(['visualiza' => $visualiza]);
        break;

    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        $sql = "INSERT INTO visualiza (email, id_pelicula, calificacion, segundo_pelicula) 
                VALUES ('$data[email]', '$data[id_pelicula]', '$data[calificacion]', '$data[segundo_pelicula]')";
        echo json_encode(['success' => $conn->query($sql)]);
        break;

    case 'PUT':
        $data = json_decode(file_get_contents('php://input'), true);
        $sql = "UPDATE visualiza SET calificacion = '$data[calificacion]', segundo_pelicula = '$data[segundo_pelicula]' 
                WHERE email = '$data[email]' AND id_pelicula = '$data[id_pelicula]'";
        echo json_encode(['success' => $conn->query($sql)]);
        break;

    case 'DELETE':
        $email = $_GET['email'];
        $id_pelicula = $_GET['id_pelicula'];
        $sql = "DELETE FROM visualiza WHERE email = '$email' AND id_pelicula = '$id_pelicula'";
        echo json_encode(['success' => $conn->query($sql)]);
        break;
}
?>
