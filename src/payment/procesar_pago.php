<?php
session_start();
require_once '../src/config/db_connect.php';

if (isset($_SESSION['email']) && isset($_POST['pantallas'])) {
    $email = $_SESSION['email'];
    $pantallas = (int)$_POST['pantallas'];

    $sql = "UPDATE `swagplay`.`usuario` SET `pantallas_seleccionadas` = ? WHERE `email` = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $pantallas, $email);

    if ($stmt->execute()) {
        header("Location: usuario.php"); // Redirige al dashboard del usuario
    } else {
        echo "Error al actualizar el plan: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: seleccion_plan.php");
    exit();
}
?>