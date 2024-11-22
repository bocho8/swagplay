<?php
session_start();

if (isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] === 1) {
    $email = $_SESSION['email'];
    $name = ucfirst(explode('@', $email)[0]);
    echo json_encode(['name' => $name, 'email' => $email]);
} else {
    echo 'no_session';
}
?>