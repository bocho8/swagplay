<?php
include 'db_connect.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = $_POST['fullname'];
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $password = $_POST['password'];
    $phone = $_POST['phone'];

    // Validate inputs
    if (!preg_match("/^[A-Z][a-z]+ [A-Za-z]+$/", $fullname)) {
        echo "Full name must start with a capital letter and have a surname.";
        exit();
    }

    if (!$email || strlen($password) < 16 || !preg_match("/^[0-9]{10}$/", $phone)) {
        echo "Please ensure all fields are correctly filled.";
        exit();
    }

    // Save user to the database (replace with actual DB insertion)
    // $conn = new mysqli('localhost', 'username', 'password', 'database');
    // $stmt = $conn->prepare("INSERT INTO users (fullname, email, password, phone) VALUES (?, ?, ?, ?)");
    // $stmt->bind_param("ssss", $fullname, $email, password_hash($password, PASSWORD_BCRYPT), $phone);
    // $stmt->execute();

    echo "Registration successful!";
    header("Location: welcome.php");
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

}
?>  