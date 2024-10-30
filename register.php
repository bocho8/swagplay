<?php
include 'db_connect.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $contrasena = $_POST['contrasena'];
    $telefono = $_POST['telefono'];

    // Validar
    if (!preg_match("/^[A-Z][a-z]+ [A-Za-z]+$/", $nombre)) {
        echo "El nombre completo debe comenzar con una letra mayúscula y tener un apellido..";
        exit();
    }

    if(!$email){
        echo "Agregue un email valido.";
        exit();
    } else if (strlen($contrasena) < 8 || !preg_match('/[A-Z]/', $contrasena) || !preg_match('/[a-z]/', $contrasena) || !preg_match('/[0-9]/', $contrasena) || !preg_match('/[\W]/', $contrasena)) {
        echo "La contraseña no es segura.";
        exit();
    } else if(!preg_match("/^[0-9]{10}$/", $telefono)){
        echo("El telefono no es valido.");
        exit();
    }

    $hashed_contrasena = password_hash($contrasena, PASSWORD_BCRYPT);

    $sql = "INSERT INTO users (nombre, email, contrasena, telefono) VALUES ('$nombre', '$email', '$hashed_contrasena', '$telefono')";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: index.html");
        exit();
    } else {
        echo "Error al registrar el usuario: " . $conn->error;
    }
}
?>
