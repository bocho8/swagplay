<?php
include '../config/db_connect.php';
include 'verificar_sesion.php';
session_start();

if (!isset($_SESSION['email'])) {
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
        $email = $data['email'];
        $id_pelicula = $data['id_pelicula'];

        if (empty($email) || empty($id_pelicula)) {
            echo json_encode(['error' => 'Email o ID de película vacíos']);
            exit();
        }

        $sql = "INSERT INTO visualiza (email, id_pelicula) VALUES ('$email', '$id_pelicula')";

        if ($conn->query($sql)) {
            echo json_encode(['success' => "creado correctamente"]);
        } else {
            echo json_encode(['error' => 'Error al insertar visualización: ' . $conn->error]);
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($data['email'], $data['id_pelicula'])) {
            echo json_encode(['error' => 'Faltan campos para actualizar']);
            exit();
        }

        $email = $data['email'];
        $id_pelicula = $data['id_pelicula'];
        $calificacion = isset($data['calificacion']) ? $data['calificacion'] : null;
        $segundo_pelicula = isset($data['segundo_pelicula']) ? $data['segundo_pelicula'] : null;

        $sql = "UPDATE visualiza SET ";
        if ($calificacion !== null) {
            $sql .= "calificacion = '$calificacion', ";
        }
        if ($segundo_pelicula !== null) {
            $sql .= "segundo_pelicula = '$segundo_pelicula' ";
        }
        $sql = rtrim($sql, ', ');
        $sql .= " WHERE email = '$email' AND id_pelicula = '$id_pelicula'";

        if ($conn->query($sql)) {
            $sqlCalificaciones = "SELECT calificacion FROM visualiza WHERE id_pelicula = '$id_pelicula' AND calificacion IS NOT NULL";
            $resultCalificaciones = $conn->query($sqlCalificaciones);
            $totalCalificaciones = 0;
            $numCalificaciones = 0;

            while ($row = $resultCalificaciones->fetch_assoc()) {
                $totalCalificaciones += $row['calificacion'];
                $numCalificaciones++;
            }

            $nuevoPromedio = $numCalificaciones > 0 ? $totalCalificaciones / $numCalificaciones : 0;

            $sqlActualizarPelicula = "UPDATE pelicula SET calificacion_usuarios = '$nuevoPromedio' WHERE id_pelicula = '$id_pelicula'";
            $conn->query($sqlActualizarPelicula);

            echo json_encode(['success' => 'Datos actualizados correctamente']);
        } else {
            echo json_encode(['error' => 'Error al actualizar los datos']);
        }
        break;

    case 'DELETE':
        verificarPermisosAdmin();
        $email = $_GET['email'];
        $id_pelicula = $_GET['id_pelicula'];
        $sql = "DELETE FROM visualiza WHERE email = '$email' AND id_pelicula = '$id_pelicula'";
        echo json_encode(['success' => $conn->query($sql)]);
        break;
}
