<?php
session_start()
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
            <div class="logo">SwagPlay</div>
            <div class="nav-links">
                <a href="#home">Inicio</a>
                <a href="#recommendations">Recomendaciones</a>
                <a href="#my-list">Mi Lista</a>
                <div class="user-profile">
                    <div class="user-avatar" id="userAvatar">U</div>
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

        <section id="recommendations" class="container">
            <h2>Recomendado para ti</h2>
            <div class="content-grid">
                <div class="content-card">
                    <img src="/placeholder.svg?height=200&amp;width=300" alt="Película Recomendada 1">
                    <div class="content-info">
                        <h3>Aventuras Estelares</h3>
                        <p>Una épica saga espacial que te llevará más allá de las estrellas.</p>
                    </div>
                </div>
                <div class="content-card">
                    <img src="/placeholder.svg?height=200&amp;width=300" alt="Película Recomendada 2">
                    <div class="content-info">
                        <h3>Misterios del Abismo</h3>
                        <p>Sumérgete en las profundidades oceánicas y descubre sus secretos.</p>
                    </div>
                </div>
                <div class="content-card">
                    <img src="/placeholder.svg?height=200&amp;width=300" alt="Película Recomendada 3">
                    <div class="content-info">
                        <h3>Crononautas</h3>
                        <p>Un viaje a través del tiempo que cambiará el destino de la humanidad.</p>
                    </div>
                </div>
                <div class="content-card">
                    <img src="/placeholder.svg?height=200&amp;width=300" alt="Película Recomendada 4">
                    <div class="content-info">
                        <h3>Sinfonia de Sueños</h3>
                        <p>Una experiencia visual y auditiva que desafía la realidad.</p>
                    </div>
                </div>
            </div>
        </section>

        <section id="my-list" class="container">
            <h2>Mi Lista</h2>
            <div class="content-grid">
                <div class="content-card">
                    <img src="/placeholder.svg?height=200&amp;width=300" alt="Película en Mi Lista 1">
                    <div class="content-info">
                        <h3>Ecos del Pasado</h3>
                        <p>Una historia conmovedora sobre la memoria y el perdón.</p>
                    </div>
                </div>
                <div class="content-card">
                    <img src="/placeholder.svg?height=200&amp;width=300" alt="Película en Mi Lista 2">
                    <div class="content-info">
                        <h3>Código Enigma</h3>
                        <p>Un thriller de espionaje que te mantendrá al borde del asiento.</p>
                    </div>
                </div>
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

    <script>
        const user = {
            name: "María",
            email: "maria@example.com"
        };

        // Actualizar la interfaz con los datos del usuario
        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('userName').textContent = user.name;
            document.getElementById('userAvatar').textContent = user.name[0].toUpperCase();
        });

        // Manejar el cierre de sesión
        document.getElementById('logoutBtn').addEventListener('click', () => {
            fetch('../auth/logout.php', { method: 'POST' })
            .then(() => {
                window.location.href = '../../index.php';
            });
        });

        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });

        window.addEventListener('scroll', () => {
            const header = document.querySelector('header');
            if (window.scrollY > 50) {
                header.style.backgroundColor = 'rgba(10, 10, 10, 0.95)';
            } else {
                header.style.backgroundColor = 'rgba(10, 10, 10, 0.8)';
            }
        });
    </script>
</body>
</html>