<?php
$servername = "localhost";
$username = "root";  // Change this to your MySQL Workbench username
$password = "";      // Your MySQL Workbench password
$dbname = "swagplay_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>