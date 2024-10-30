<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pago - SwagPlay</title>
</head>
<body>
    <h2>Pago de suscripción</h2>
    <form action="process_payment.php" method="POST">
        <label for="cardholder_name">Nombre del titular:</label>
        <input type="text" id="cardholder_name" name="cardholder_name" required>

        <label for="card_number">Número de tarjeta:</label>
        <input type="text" id="card_number" name="card_number" maxlength="16" required>

        <label for="expiry_date">Fecha de vencimiento (MM/YYYY):</label>
        <input type="text" id="expiry_date" name="expiry_date" maxlength="7" required>

        <label for="cvv">CVV:</label>
        <input type="text" id="cvv" name="cvv" maxlength="4" required>

        <button type="submit">Pagar y activar plan</button>
    </form>
</body>
</html>