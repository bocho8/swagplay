<?php
include '../config/db_connect.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $password = $_POST['contrasena'];

    if (!$email) {
        echo "Agregue un email válido.";
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
            echo "success";
        } else {
            echo "Credenciales de inicio de sesión inválidas.";
        }
    } else {
        // Si es el email del administrador y no existe, crearlo
        if ($email === 'admin@swagplay.com') {
            $adminPassword = password_hash('admin', PASSWORD_BCRYPT); // Contraseña predeterminada: admin
            $sqlCreateAdmin = "INSERT INTO usuario (email, contrasena) VALUES ('$email', '$adminPassword')";
            if ($conn->query($sqlCreateAdmin) === TRUE) {
                echo "Usuario administrador creado exitosamente. Intente iniciar sesión nuevamente.";
            } else {
                echo "Error al crear el usuario administrador: " . $conn->error;
            }
        } else {
            echo "Credenciales de inicio de sesión inválidas.";
        }
    }
}
?>
