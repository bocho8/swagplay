<?php
session_start();

if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === 1) {
    $email = $_SESSION['email'];
    $name = ucfirst(explode('@', $email)[0]); // Deriva el nombre del email
    echo json_encode(['name' => $name, 'email' => $email]);
} else {
    http_response_code(403);
    echo json_encode(['error' => 'No autorizado']);
}
?>