<?php
include 'db_connect.php';
session_start();

// Verificar si el usuario ha iniciado sesión y ha pagado
if (!isset($_SESSION['user_id']) || $_SESSION['is_paid'] == 0) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Obtener el plan del usuario
$sql = "SELECT plan FROM users WHERE id = '$user_id'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$plan = $row['plan'];

// Determinar el número de perfiles según el plan
$num_profiles = 1;
if ($plan == '2 pantallas') {
    $num_profiles = 2;
} elseif ($plan == '4 pantallas') {
    $num_profiles = 4;
}

echo "<h2>Elige tu perfil</h2>";

for ($i = 1; $i <= $num_profiles; $i++) {
    echo "<a href='profile.php?profile=$i'>Perfil $i</a><br>";
}
?>