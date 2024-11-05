<?php
include '../config/db_connect.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $password = $_POST['contrasena'];

    if (!$email) {
        echo "Agregue un email valido.";
        exit();
    } else if (strlen($password) < 8) {
        echo "La contraseña debe tener al menos 8 caracteres.";
        exit();
    }

    $sql = "SELECT email, contrasena FROM usuario WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['contrasena'])) {
            $_SESSION['is_logged_in'] = 1;
            $_SESSION['email'] = $email;
            header("Location: ../views/welcome.php");
        } else {
            echo "Credenciales de inicio de sesión inválidas.";
        }
    } else {
        echo "Credenciales de inicio de sesión inválidas.";
    }
}
?>