<?php
include '../config/db_connect.php';
include 'verificar_sesion.php';
session_start();

// Verificamos que el usuario esté autenticado
if (!isset($_SESSION['email'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Acceso denegado']);
    exit();
}

// Procesamos las diferentes peticiones HTTP
switch ($_SERVER['REQUEST_METHOD']) {
    // Obtener las suscripciones del usuario autenticado
    case 'GET':
        $email = $_SESSION['email']; // Usamos el email del usuario en sesión
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

    // Actualizar el plan de suscripción del usuario
    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);

        // Validamos que el campo 'plan' esté presente
        if (!isset($data['plan'])) {
            echo json_encode(['error' => 'Falta el campo del plan de suscripción']);
            exit();
        }

        $email = $_SESSION['email']; // Usamos el email del usuario en sesión
        $plan = $data['plan'];

        // Comprobamos si el usuario ya tiene una suscripción
        $sqlCheck = "SELECT * FROM suscripcion WHERE email = '$email'";
        $resultCheck = $conn->query($sqlCheck);

        if ($resultCheck->num_rows > 0) {
            // Si tiene una suscripción, actualizamos el plan
            $sqlUpdate = "UPDATE suscripcion SET nombre = '$plan' WHERE email = '$email'";
            if ($conn->query($sqlUpdate)) {
                echo json_encode(['success' => 'Plan de suscripción actualizado']);
            } else {
                echo json_encode(['error' => 'Error al actualizar el plan de suscripción']);
            }
        } else {
            // Si no tiene suscripción, la creamos
            $sqlInsert = "INSERT INTO suscripcion (email, nombre) VALUES ('$email', '$plan')";
            if ($conn->query($sqlInsert)) {
                echo json_encode(['success' => 'Nueva suscripción creada']);
            } else {
                echo json_encode(['error' => 'Error al crear la suscripción']);
            }
        }
        break;

    // Eliminar una suscripción del usuario
    case 'DELETE':
        $data = json_decode(file_get_contents('php://input'), true);

        // Validamos que el campo 'pantallas_simultaneas' esté presente
        if (!isset($data['pantallas_simultaneas'])) {
            echo json_encode(['error' => 'Falta el campo de pantallas simultáneas']);
            exit();
        }

        $email = $_SESSION['email']; // Usamos el email del usuario en sesión
        $pantallasSimultaneas = $data['pantallas_simultaneas'];

        // Eliminamos la suscripción
        $sql = "DELETE FROM suscripcion WHERE email = '$email' AND pantallas_simultaneas = '$pantallasSimultaneas'";

        if ($conn->query($sql)) {
            echo json_encode(['success' => 'Suscripción eliminada']);
        } else {
            echo json_encode(['error' => 'Error al eliminar la suscripción']);
        }
        break;

    // Si el método no es GET, POST ni DELETE, respondemos con un error
    default:
        echo json_encode(['error' => 'Método no permitido']);
        break;
}
?>
