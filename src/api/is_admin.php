<?php

include '../config/db_connect.php';

function verificarPermisosAdmin() {
    if (!isset($_SESSION['email']) || $_SESSION['email'] !== 'admin@swagplay.com') {
        http_response_code(403); // No autorizado
        exit(json_encode(['error' => 'Acceso denegado. Solo administradores pueden realizar esta acción.']));
    }
}