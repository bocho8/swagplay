<?php
include 'db_connect.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'];

    // Validar los campos
    if (!$email || strlen($password) < 8) {
        echo "Invalid login credentials.";
        exit();
    }

    // Buscar el usuario en la base de datos
    $stmt = $conn->prepare("SELECT password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->bind_result($stored_hashed_password);
    $stmt->fetch();
    $stmt->close();

    // Verificar contraseña
    if ($stored_hashed_password && password_verify($password, $stored_hashed_password)) {
        // Iniciar sesión
        $_SESSION['loggedin'] = true;
        header("Location: welcome.php");
        exit();
    } else {
        echo "Invalid login credentials.";
        exit();
    }
}
?>
