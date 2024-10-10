<?php
include 'db_connect.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'];

    // Validate inputs

    if (password_verify($password, $stored_hashed_password)) {
        // Password is correct, log the user in
    } else {
        // Invalid password
    }
    

    if (!$email || strlen($password) < 16) {
        echo "Invalid login credentials.";
        exit();
        
    }

    // Database login logic (replace with actual DB query)
    // $conn = new mysqli('localhost', 'username', 'password', 'database');
    // Validate user from DB here...

    $_SESSION['loggedin'] = true;
    header("Location: welcome.php");
}
?>
