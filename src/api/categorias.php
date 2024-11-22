<?php
include '../config/db_connect.php';
include 'verificar_sesion.php';
session_start();

if (!isset($_SESSION['email'])) {
    http_response_code(403);
    exit();
}

verificarPermisosGestor();

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        $sql = "SELECT id_categoria, categoria FROM categorias";
        $result = $conn->query($sql);
        $categorias = [];
        while ($row = $result->fetch_assoc()) {
            $categorias[] = $row;
        }
        echo json_encode(['categorias' => $categorias]);
        break;

    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
    
        $sqlInsertCategoria = "INSERT INTO categorias (categoria) 
                               VALUES ('$data[categoria]')";
        
        echo json_encode(['success' => $conn->query($sqlInsertCategoria)]);
        break;
        

    case 'PUT':
        $data = json_decode(file_get_contents('php://input'), true);
        $sql = "UPDATE categorias SET categoria = '$data[categoria]' WHERE id_categoria = '{$data['id_categoria']}'";
        echo json_encode(['success' => $conn->query($sql)]);
        break;

    case 'DELETE':
        $id_categoria = $_GET['id_categoria'];
    
        // Eliminar relaciones primero
        $sqlRelacion = "DELETE FROM pelicula_categoria WHERE id_categoria = '$id_categoria'";
        $conn->query($sqlRelacion);
    
        // Luego eliminar la categoría
        $sql = "DELETE FROM categorias WHERE id_categoria = '$id_categoria'";
        echo json_encode(['success' => $conn->query($sql)]);
        break;
        
}
?>
