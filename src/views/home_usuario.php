<?php
session_start();

if(!isset($_SESSION["email"])){
    header("Location: ../../index.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SwagPlay - Tu Panel de Usuario</title>
    <link rel="stylesheet" href="../../css/panel_style.css">
</head>
<body>
    <header>
        <nav class="container">
            <div class="logo">
                <a href="../../index.php">🎬SwagPlay</a>
            </div>
            <div class="nav-links">
                <a href="#home">Inicio</a>
                <a href="#allMovies">Peliculas</a>
                <div class="user-profile">
                    <a href="usuario_panel.php" class="user-avatar" id="userAvatar">U</a>
                    <button class="logout-button" id="logoutBtn">Cerrar Sesión</button>
                </div>
            </div>
        </nav>
    </header>

    <main>
        <section id="home" class="welcome-section container">
            <h1>Bienvenido de vuelta, <span id="userName">Usuario</span></h1>
            <p>Continúa disfrutando de tu contenido favorito o descubre algo nuevo.</p>
        </section>

        <section id="allMovies" class="container">
            <h2>Películas </h2>
            <div class="content-grid" id="moviesGrid"></div>
        </section>

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

    <script src="../js/home_usuario.js"></script>
</body>
</html>