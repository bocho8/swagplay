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
        $sql = "SELECT s.pantallas_simultaneas, s.nombre AS plan
                FROM suscripcion s
                WHERE s.email = '$email'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $suscripciones = [];
            while ($row = $result->fetch_assoc()) {
                $suscripciones[] = $row;
            }
            echo json_encode(['suscripciones' => $suscripciones]);
        } else {
            echo json_encode(['error' => 'No se encontraron suscripciones']);
        }
        break;


    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);


        if (!isset($data['plan'])) {
            echo json_encode(['error' => 'Falta el campo del plan de suscripción']);
            exit();
        }

        $email = $_SESSION['email'];
        $plan = $data['plan'];


        $sqlCheck = "SELECT * FROM suscripcion WHERE email = '$email'";
        $resultCheck = $conn->query($sqlCheck);

        if ($resultCheck->num_rows > 0) {

            $sqlUpdate = "UPDATE suscripcion SET nombre = '$plan' WHERE email = '$email'";
            if ($conn->query($sqlUpdate)) {
                echo json_encode(['success' => 'Plan de suscripción actualizado']);
            } else {
                echo json_encode(['error' => 'Error al actualizar el plan de suscripción']);
            }
        } else {

            $sqlInsert = "INSERT INTO suscripcion (email, nombre) VALUES ('$email', '$plan')";
            if ($conn->query($sqlInsert)) {
                echo json_encode(['success' => 'Nueva suscripción creada']);
            } else {
                echo json_encode(['error' => 'Error al crear la suscripción']);
            }
        }
        break;


    case 'DELETE':
        $data = json_decode(file_get_contents('php://input'), true);


        if (!isset($data['pantallas_simultaneas'])) {
            echo json_encode(['error' => 'Falta el campo de pantallas simultáneas']);
            exit();
        }

        $email = $_SESSION['email'];
        $pantallasSimultaneas = $data['pantallas_simultaneas'];


        $sql = "DELETE FROM suscripcion WHERE email = '$email' AND pantallas_simultaneas = '$pantallasSimultaneas'";

        if ($conn->query($sql)) {
            echo json_encode(['success' => 'Suscripción eliminada']);
        } else {
            echo json_encode(['error' => 'Error al eliminar la suscripción']);
        }
        break;


    default:
        echo json_encode(['error' => 'Método no permitido']);
        break;
}
