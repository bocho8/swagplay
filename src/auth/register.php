<?php
include '../config/db_connect.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $contrasena = $_POST['contrasena'];
    $telefono = $_POST['telefono'];

    if (!$email) {
        echo "Agregue un email valido.";
        exit();
    } else if (strlen($contrasena) < 8 || !preg_match('/[A-Z]/', $contrasena) || !preg_match('/[a-z]/', $contrasena) || !preg_match('/[0-9]/', $contrasena) || !preg_match('/[\W]/', $contrasena)) {
        echo "La contraseña no es segura.";
        exit();
    } else if (!preg_match("/^[0-9]{10}$/", $telefono)) {
        echo "El telefono no es valido.";
        exit();
    }

    $hashed_contrasena = password_hash($contrasena, PASSWORD_BCRYPT);

    $sql = "INSERT INTO `usuario` (`email`, `contrasena`, `telefono`) VALUES ('$email', '$hashed_contrasena', '$telefono')";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: ../../src/views/planes.html");
        exit();
    } else {
        echo "Error al registrar el usuario: " . $conn->error;
    }
}
?>