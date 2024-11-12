<?php
session_start();
include('../config/db_connect.php');

$email = $_SESSION['email'];
$consulta_perfiles = "SELECT nombre FROM perfiles WHERE email='$email' LIMIT 4";
$resultado_perfiles = mysqli_query($conn, $consulta_perfiles);

if (!$resultado_perfiles) {
    die('Error en la consulta: ' . mysqli_error($conn));
}

$perfiles = [];
while ($perfil = mysqli_fetch_assoc($resultado_perfiles)) {
    $perfiles[] = $perfil;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Usuario.css">
    <link rel="Icon" href="imagenes/V.png" type="image/x-icon">

    <title>Gestión de Perfiles</title>
</head>
<body>
    <div class="container">
        <h1>Perfiles de Usuario</h1>
        <div class="profile-list">
            <?php foreach ($perfiles as $usuario): ?>
            <div class="profile-card">
                <h2><?php echo htmlspecialchars($usuario['nombre']); ?></h2>
                <button onclick="location.href='EditarPerfil.php?user=<?php echo urlencode($usuario['nombre']); ?>'">Modificar</button>
                <button onclick="location.href='EliminarPerfil.php?nombre=<?php echo urlencode($usuario['nombre']); ?>'">Eliminar</button>
            </div>
            <?php endforeach; ?>
        </div>

        <?php if (count($perfiles) < 4): ?>
        <div class="create-profile">
            <h2>Crear nuevo perfil</h2>
            <form action="src/perfiles/Crearperfil.php" method="post">
                <input type="text" name="nombre" placeholder="Nombre del perfil" required>
                <input type="submit" value="Crear Perfil">
            </form>
        </div>
        <?php else: ?>
        <p>Ya has alcanzado el límite máximo de 4 perfiles.</p>
        <?php endif; ?>
    </div>
</body>
</html>