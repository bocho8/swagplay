<?php
include 'db_connect.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $cardholder_name = $_POST['cardholder_name'];
    $card_number = $_POST['card_number'];
    $expiry_date = $_POST['expiry_date'];
    $cvv = $_POST['cvv'];

    // Insertar los datos de la tarjeta en la base de datos
    $sql = "INSERT INTO credit_cards (user_id, cardholder_name, card_number, expiry_date, cvv) 
            VALUES ('$user_id', '$cardholder_name', '$card_number', '$expiry_date', '$cvv')";

    if ($conn->query($sql) === TRUE) {
        // Actualizar el estado de pago y activar el plan
        $sql_update = "UPDATE users SET is_paid = 1 WHERE id = '$user_id'";
        if ($conn->query($sql_update) === TRUE) {
            header("Location: elegir_perfil.php");
            exit();
        } else {
            echo "Error al actualizar el estado de pago: " . $conn->error;
        }
    } else {
        echo "Error al procesar el pago: " . $conn->error;
    }
}
?>