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

        <section id="allMovies" class="container">
            <h2>Películas Disponibles</h2>
            <div class="content-grid" id="moviesGrid">
                <!-- Aquí se agregarán todas las películas dinámicamente -->
            </div>
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

    <script>
        // Actualizar la interfaz con los datos del usuario
        document.addEventListener('DOMContentLoaded', () => {
            // Obtener todas las películas desde el servidor
            fetch('../api/peliculas.php')
            .then(response => response.json())
            .then(data => {
                const peliculas = data.peliculas;
                const moviesGrid = document.getElementById('moviesGrid');

                // Generar HTML para cada película
                peliculas.forEach(pelicula => {
                    const peliculaCard = document.createElement('div');
                    peliculaCard.classList.add('content-card');
                    peliculaCard.innerHTML = `
                        <img src="${pelicula.foto}" alt="${pelicula.titulo}" width="300" height="200">
                        <div class="content-info">
                            <h3>${pelicula.titulo}</h3>
                            <p>${pelicula.descripcion}</p>
                        </div>
                    `;
                    moviesGrid.appendChild(peliculaCard);
                });
            })
            .catch(error => {
                console.error('Error al cargar las películas:', error);
            });

            fetch('../auth/get_user_data.php')
            .then(response => {
                if (!response.ok) throw new Error('Usuario no autorizado');
                return response.json();
            })
            .then(user => {
                document.getElementById('userName').textContent = user.name;
                document.getElementById('userAvatar').textContent = user.name[0].toUpperCase();
            })
            .catch(error => {
                console.error('Error:', error);
                window.location.href = '../../index.php'; // Redirige si no hay sesión
            });
        });

        // Manejar el cierre de sesión
        document.getElementById('logoutBtn').addEventListener('click', () => {
            fetch('../auth/logout.php', { method: 'POST' })
            .then(() => {
                window.location.href = '../../index';
            });
        });

        // gracias chat
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