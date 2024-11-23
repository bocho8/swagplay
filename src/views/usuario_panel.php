<?php
session_start();
include '../api/verificar_sesion.php';

if (!isset($_SESSION['email'])) {
    header("Location: ../../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SwagPlay - Panel de Usuario</title>
    <link rel="stylesheet" href="../../css/admin_panel.css">
</head>
<body>

    <div id="notification" class="notification"></div>

    <header>
        <nav class="container">
            <div class="logo">
            <img src="public/assets/images/SIMPLETRANSPARENTE.PNG" 
            width="50" 
             height="50" />
                <a href="../../index.php">SwagPlay</a>
            </div>
            <div class="nav-links">
                <a href="#usuario">Usuario</a>
                <a href="#suscripciones">Suscripciones</a>
                <div class="auth-buttons">
                    <button id="logoutBtn">Cerrar Sesión</button>
                </div>
            </div>
        </nav>
    </header>

    <main class="container">
        <!-- Sección de Usuario -->
        <section id="usuario" class="section">
            <h2>Gestión de Usuario</h2>
            <form id="usuarioForm">
                <h3>Actualizar Información</h3>
                <label for="contrasena">Contraseña:</label>
                <input type="password" id="contrasena" required>
                <label for="telefono">Teléfono:</label>
                <input type="text" id="telefono" required>
                <label for="ciudad">Ciudad:</label>
                <input type="text" id="ciudad" required>
                <label for="pais">País:</label>
                <input type="text" id="pais" required>
                <label for="tarjetaNumero">Número de Tarjeta:</label>
                <input type="text" id="tarjetaNumero" required>
                <label for="cvv">CVV:</label>
                <input type="password" id="cvv" required>
                <label for="nombreTarjeta">Nombre en la Tarjeta:</label>
                <input type="text" id="nombreTarjeta" required>
                <button type="button" id="guardarCambiosBtn" class="btn-add">Guardar Cambios</button>
            </form>
        </section>

        <!-- Sección de Suscripciones -->
        <section id="suscripciones" class="section">
            <h2>Gestión de Suscripciones</h2>
            <form id="suscripcionForm">
                <h3>Seleccionar Plan de Suscripción</h3>
                <label for="planSuscripcion">Elige un plan:</label>
                <select id="planSuscripcion" required>
                    <option value="1">1 Pantalla</option>
                    <option value="2">2 Pantallas</option>
                    <option value="4">4 Pantallas</option>
                </select>
                <button type="button" id="elegirPlanBtn" class="btn-add">Elegir Plan</button>
            </form>
            <table>
                <thead>
                    <tr>
                        <th>Plan</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="suscripcionesTabla"></tbody>
            </table>
        </section>

    </main>

    <footer>
        <div class="container">
            <p>&copy; 2024 SwagPlay. Todos los derechos reservados.</p>
        </div>
    </footer>
    <script src="../js/panel_usuario.js"></script>
</body>
</html>
