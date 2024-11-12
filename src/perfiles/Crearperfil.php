<?php
session_start(); // Iniciar sesión
include('../config/db_connect.php'); // Asegúrate de que la conexión es correcta

$email = $_SESSION['email'];
$nombre_perfil = $_POST['nombre'];

// Limitar a 4 perfiles por usuario
$consulta_perfiles = "SELECT COUNT(*) as total FROM perfiles WHERE email='$email'";
$resultado_perfiles = mysqli_query($conn, $consulta_perfiles);
$total_perfiles = mysqli_fetch_assoc($resultado_perfiles)['total'];

if ($total_perfiles >= 4) {
    echo "No puedes crear más de 4 perfiles.";
} else {
    // Insertar el nuevo perfil
    $insertar_perfil = "INSERT INTO perfiles (nombre, email) VALUES ('$nombre_perfil', '$email')";
    
    if (mysqli_query($conn, $insertar_perfil)) {
        header("Location: Usuario.php");
        exit();
    } else {
        echo "Error al crear el perfil: " . mysqli_error($conn);
    }
}
?>