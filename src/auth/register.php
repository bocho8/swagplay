<?php
include '../config/db_connect.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $contrasena = $_POST['contrasena'];
    $telefono = $_POST['telefono'];

    if (preg_match('/.*@swagplay\.com$/', $email)) {
        echo "Error al registrar el usuario: no se puede usar el dominio swagplay.com";
        exit();
    }

    if (!$email) {
        echo "Agregue un email válido.";
        exit();
    } 
    
    if (strlen($contrasena) < 8 || 
        !preg_match('/[A-Z]/', $contrasena) || 
        !preg_match('/[a-z]/', $contrasena) || 
        !preg_match('/[0-9]/', $contrasena) || 
        !preg_match('/[\W]/', $contrasena)) {
        echo "La contraseña no es segura.";
        exit();
    }

    if (!preg_match("/^[0-9]{10}$/", $telefono)) {
        echo "El teléfono no es válido.";
        exit();
    }

    $hashed_contrasena = password_hash($contrasena, PASSWORD_BCRYPT);

    $sql = "INSERT INTO `usuario` (`email`, `contrasena`, `telefono`) VALUES ('$email', '$hashed_contrasena', '$telefono')";
    
    if ($conn->query($sql) === TRUE) {
        echo "Registro correcto.";
        exit();
    } else {
        echo "Error al registrar el usuario: " . $conn->error;
    }
}
?>
