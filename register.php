<?php
include 'db_connect.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = $_POST['fullname'];
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'];
    $phone = $_POST['phone'];

    // Validar
    if (!preg_match("/^[A-Z][a-z]+ [A-Za-z]+$/", $fullname)) {
        echo "Full name must start with a capital letter and have a surname.";
        exit();
    }

    if (!$email || strlen($password) < 8 || !preg_match("/^[0-9]{10}$/", $phone)) {
        echo "Please ensure all fields are correctly filled.";
        exit();
    }

    // Hashear la contraseña
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Guardar usuario en la base de datos
    $stmt = $conn->prepare("INSERT INTO users (fullname, email, password, phone) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $fullname, $email, $hashed_password, $phone);
    
    if ($stmt->execute()) {
        header("Location: welcome.php");
        exit();
    } else {
        echo "Error al registrar el usuario.";
    }
}
?>
