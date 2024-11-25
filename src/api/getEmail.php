<?php

include '../config/db_connect.php';
session_start();

if (!isset($_SESSION['email'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Acceso denegado']);
    exit();
}

echo $email = $_SESSION['email'];