<?php
include '../config/db_connect.php';

function verificarPermisosAdmin() {
    if (!isset($_SESSION['email']) || $_SESSION['email'] !== 'admin@swagplay.com') {
        http_response_code(403); // No autorizado
        exit(json_encode(['error' => 'Acceso denegado. Solo administradores pueden realizar esta accion.']));
    }
}

function verificarPermisosGestor() {
    if (!isset($_SESSION['email']) || !preg_match('/.*(@swagplay\.com)$/', $_SESSION["email"]) ? true : false) {
        http_response_code(403); // No autorizado
        exit(json_encode(['error' => 'Acceso denegado. Solo gestores pueden realizar esta accion.']));
    }
}

function verificarPermisosUsuario() {
    if (!isset($_SESSION['email'])) {
        http_response_code(403); // No autorizado
        exit(json_encode(['error' => 'Acceso denegado. Solo usuarios pueden realizar esta accion.']));
    }
}