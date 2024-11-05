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

    // Buscar el usuario en la base de datos
    $sql = "SELECT email, contrasena FROM usuario WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $stored_hashed_password = $row['contrasena'];

        // Verificar la contraseña
        if (password_verify($password, $stored_hashed_password)) {
            // Establecer variables de sesión
            $_SESSION['email'] = $row['email'];
            header("Location: welcome.php");
            exit();
        }
    } else {
        echo "Credenciales de inicio de sesión inválidas.";
    }
}
?>
