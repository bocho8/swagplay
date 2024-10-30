<?php
include 'db_connect.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'];

    // Validar email y contraseña
    if (!$email) {
        echo "Agregue un email valido.";
        exit();
    } else if (strlen($password) < 8) {
        echo "La contraseña debe tener al menos 8 caracteres.";
        exit();
    }

    // Buscar el usuario en la base de datos
    $sql = "SELECT id, fullname, password FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $stored_hashed_password = $row['password'];

        // Verificar la contraseña
        if (password_verify($password, $stored_hashed_password)) {
            // Establecer variables de sesión
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['fullname'] = $row['fullname'];
            header("Location: welcome.php");
            exit();
        } else {
            echo "Credenciales de inicio de sesión inválidas.";
        }
    } else {
        echo "Credenciales de inicio de sesión inválidas.";
    }
}
?>
