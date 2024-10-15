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
        echo "El nombre completo debe comenzar con una letra mayúscula y tener un apellido..";
        exit();
    }

    if(!$email){
        echo "Agregue un email valido.";
        exit();
    } else if (strlen($password) < 8 || !preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password) || !preg_match('/[0-9]/', $password) || !preg_match('/[\W]/', $password)) {
        echo "La contraseña no es segura.";
        exit();
    } else if(!preg_match("/^[0-9]{10}$/", $phone)){
        echo("El telefono no es valido.");
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    $sql = "INSERT INTO usuario (fullname, email, password, phone) VALUES ('$fullname', '$email', '$hashed_password', '$phone')";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: welcome.php");
        exit();
    } else {
        echo "Error al registrar el usuario: " . $conn->error;
    }
}
?>
