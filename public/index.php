<?php
session_start();

$tipo_usuario = '';

if (isset($_SESSION["email"]) && $_SESSION["is_logged_in"]){
    switch ($_SESSION["email"]) {
        case 'admin@swagplay.com':
            $tipo_usuario = 'admin';
            break;
        case preg_match('/.*(@swagplay\.com)$/', $_SESSION["email"]) ? true : false: // gracias regex101.com
            $tipo_usuario = 'gestor';
            break;
        default:
            $tipo_usuario = 'usuario';
    }
};

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SwagPlay - Streaming de Próxima Generación</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <nav class="container">
            <div class="logo">
                <a href="index">🎬SwagPlay</a>
            </div>
            <div class="nav-links">
                <a href="#home">Inicio</a>
                <a href="src/views/panel_usuario.php">Películas</a>
                <div class="user-profile" id="user-profile"></div>
                <div class="auth-buttons">
                    <?php if ($tipo_usuario == 'admin'): ?>
                        <a href="src/views/admin_panel.php">Panel Administrador</a>
                        <button class="auth-button" id="logoutBtn">Cerrar Sesión</button>
                    <?php elseif($tipo_usuario == 'gestor'): ?>
                        <a href="src/views/gestor_panel.php">Panel Gestor</a>
                        <button class="auth-button" id="logoutBtn">Cerrar Sesión</button>
                    <?php elseif($tipo_usuario == 'usuario'): ?>
                        <button class="auth-button" id="logoutBtn">Cerrar Sesión</button>
                    <?php elseif($tipo_usuario == ''): ?>
                        <button class="auth-button" id="loginBtn">Iniciar Sesión</button>
                        <button class="auth-button" id="registerBtn">Registrarse</button>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
    </header>

    <main>
        <section id="home" class="hero">
            <div class="container hero-content">
                <h1>El Futuro del Streaming</h1>
                <p>Experimenta el futuro del entretenimiento con 4K nítido y sonido envolvente. Tu puerta a mundos infinitos te espera.</p>
                <?php if (isset($_SESSION["email"]) && $_SESSION["is_logged_in"]): ?>
                    <a class="cta-button" href="src/views/panel_usuario.php">Comienza a Ver Peliculas</a>
                <?php else : ?>
                    <a class="cta-button" href="#">Comienza tu Viaje</a>
                <?php endif; ?>
            </div>
        </section>

        <section id="featured" class="featured">
            <div class="container">
                <h2>Tendencias Actuales</h2>
                <div id="content-grid" class="content-grid"></div>
            </div>
        </section>
    </main>

    <footer>
        <div class="container">
            <div class="footer-links">
                <a href="#">Sobre Nosotros</a>
                <a href="#">Centro de Ayuda</a>
                <a href="#">Términos de Uso</a>
                <a href="#">Política de Privacidad</a>
            </div>
            <p>&copy; 2023 SwagPlay. Todos los derechos reservados.</p>
        </div>
    </footer>

    <div id="loginModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h3>Iniciar Sesión</h3>
            <form id="loginForm">
                <input type="email" name="email" placeholder="Correo Electrónico" required>
                <input type="password" name="contrasena" placeholder="Contraseña" required>
                <button type="submit">Iniciar Sesión</button>
            </form>
            <div id="login_response" class="response"></div>
        </div>
    </div>

    <div id="registerModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h3>Registrarse</h3>
            <form id="registerForm">
                <input type="email" name="email" placeholder="Correo Electrónico" required>
                <input type="password" name="contrasena" placeholder="Contraseña" required>
                <input type="tel" name="telefono" placeholder="Teléfono" pattern="^[0-9]{10}" required>
                <button type="submit">Registrarse</button>
            </form>
            <div id="register_response" class="response"></div>
        </div>
    </div>

    <script src="js/index.js"></script>
</body>
</html>