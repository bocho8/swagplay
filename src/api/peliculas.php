<?php
include '../../config/db_connect.php';
session_start();

if (!isset($_SESSION['email']) || $_SESSION['email'] !== 'admin@swagplay.com') {
    http_response_code(403);
    exit();
}

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        $sql = "SELECT * FROM pelicula";
        $result = $conn->query($sql);
        $peliculas = [];
        while ($row = $result->fetch_assoc()) {
            $peliculas[] = $row;
        }
        echo json_encode(['peliculas' => $peliculas]);
        break;

    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        $sql = "INSERT INTO pelicula (titulo, descripcion, calificacion_usuarios, foto, lanzamiento) 
                VALUES ('$data[titulo]', '$data[descripcion]', '$data[calificacion_usuarios]', '$data[foto]', '$data[lanzamiento]')";
        echo json_encode(['success' => $conn->query($sql)]);
        break;

    case 'PUT':
        $data = json_decode(file_get_contents('php://input'), true);
        $sql = "UPDATE pelicula SET titulo = '$data[titulo]', descripcion = '$data[descripcion]', 
                calificacion_usuarios = '$data[calificacion_usuarios]', foto = '$data[foto]', 
                lanzamiento = '$data[lanzamiento]' WHERE id_pelicula = '$data[id_pelicula]'";
        echo json_encode(['success' => $conn->query($sql)]);
        break;

    case 'DELETE':
        $id_pelicula = $_GET['id_pelicula'];
        $sql = "DELETE FROM pelicula WHERE id_pelicula = '$id_pelicula'";
        echo json_encode(['success' => $conn->query($sql)]);
        break;
}
?>