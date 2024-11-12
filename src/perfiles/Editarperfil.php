<?php
session_start();
include('../config/db_connect.php');

if (isset($_GET['user'])) {
    $nombre = mysqli_real_escape_string($conn, $_GET['user']);
    $email = $_SESSION['email'];
    
    $consulta = "SELECT * FROM perfiles WHERE nombre='$nombre' AND email='$email'";
    $resultado = mysqli_query($conn, $consulta);

    if ($resultado) {
        $perfil = mysqli_fetch_assoc($resultado);
    } else {
        die('Error al consultar el perfil: ' . mysqli_error($conn));
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_actualizado = mysqli_real_escape_string($conn, $_POST['nombre']);
    $email = $_SESSION['email'];
    
    $consulta_actualizar = "UPDATE perfiles SET nombre='$nombre_actualizado' WHERE nombre='$nombre' AND email='$email'";
    
    if (mysqli_query($conn, $consulta_actualizar)) {
        header("Location: Usuario.php");
        exit();
    } else {
        echo "Error al actualizar el perfil: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Usuario.css">
    <link rel="Icon" href="imagenes/V.png" type="image/x-icon">

    <title>Editar Perfil</title>
</head>
<body>
    <div class="container">
        <h1>Editar Perfil</h1>
        <form action="EditarPerfil.php?user=<?php echo urlencode($nombre); ?>" method="post">
            <input type="text" name="nombre" value="<?php echo htmlspecialchars($perfil['nombre']); ?>" required>
            <!-- Imagenes de perfil -->
            <h3>Selecciona una imagen de perfil</h3>
            <div class="image-options">
                <!-- Añade más opciones de imagen según sea necesario -->
            </div>
            <input type="submit" value="Actualizar Perfil">
        </form>
    </div>
</body>
</html>