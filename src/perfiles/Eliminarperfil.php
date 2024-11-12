<?php
session_start();
include('../config/db_connect.php');

$email = $_SESSION['email'];

if (isset($_GET['nombre'])) {
    $nombre_perfil = $_GET['nombre'];

    $consulta_cuenta_perfiles = "SELECT COUNT(*) as total FROM perfiles WHERE email='$email'";
    $resultado_cuenta = mysqli_query($conn, $consulta_cuenta_perfiles);

    if ($resultado_cuenta) {
        $fila_cuenta = mysqli_fetch_assoc($resultado_cuenta);

        if ($fila_cuenta['total'] > 1) {
            $consulta_perfil = "SELECT * FROM perfiles WHERE nombre='$nombre_perfil' AND email='$email'";
            $resultado_perfil = mysqli_query($conn, $consulta_perfil);

            if (mysqli_num_rows($resultado_perfil) > 0) {
                $eliminar_perfil = "DELETE FROM perfiles WHERE nombre='$nombre_perfil' AND email='$email'";
                if (mysqli_query($conn, $eliminar_perfil)) {
                    header("Location: Usuario.php");
                    exit();
                } else {
                    echo "Error al eliminar el perfil: " . mysqli_error($conn);
                }
            } else {
                echo "Perfil no encontrado o no pertenece al usuario.";
            }
        } else {
            header("Location: Usuario.php?error=ultimo_perfil");
            exit();
        }
    } else {
        echo "Error al contar los perfiles: " . mysqli_error($conn);
    }
}
?>