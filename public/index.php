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
    <script src="js/handle_nav.js"></script>
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
                <a href="#" class="cta-button">Comienza tu Viaje</a>
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

    <script>
    document.addEventListener('DOMContentLoaded', () => {

        fetch('src/api/peliculas.php')
        .then(response => {
            if (!response.ok) {
                throw new Error(`Error en la API: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            const grid = document.getElementById('content-grid');
            grid.innerHTML = ''; // Limpiar contenido previo (si existe)

            if (data.peliculas.length === 0) {
                grid.innerHTML = '<p>No hay películas disponibles.</p>';
                return;
            }

            // Generar dinámicamente las tarjetas de películas
            data.peliculas.forEach(pelicula => {
                const card = document.createElement('div');
                card.className = 'content-card';

                card.innerHTML = `
                    <img src="${pelicula.foto}" alt="${pelicula.titulo}">
                    <div class="content-info">
                        <h3>${pelicula.titulo}</h3>
                        <p>${pelicula.descripcion}</p>
                        <p>Calificación: ${pelicula.calificacion_usuarios || 'N/A'}</p>
                        <p>Lanzamiento: ${pelicula.lanzamiento}</p>
                    </div>
                `;
                grid.appendChild(card);
            });
        })
        .catch(error => {
            console.error('Error al obtener películas:', error);
            const grid = document.getElementById('content-grid');
            grid.innerHTML = '<p>Error al cargar las películas.</p>';
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

        const logoutBtn = document.getElementById('logoutBtn');
        console.log(logoutBtn);
        if (logoutBtn) {
            logoutBtn.addEventListener('click', () => {
                fetch('src/auth/logout.php', { method: 'POST' })
                .then(() => {
                    window.location.href = 'index.php';
                });
            });
        }

        const loginBtn = document.getElementById("loginBtn");
        const registerBtn = document.getElementById("registerBtn");
        const loginModal = document.getElementById("loginModal");
        const registerModal = document.getElementById("registerModal");
        const closeBtns = document.getElementsByClassName("close");

        loginBtn.onclick = () => loginModal.style.display = "block";
        registerBtn.onclick = () => registerModal.style.display = "block";

        Array.from(closeBtns).forEach(btn => {
            btn.onclick = function() {
                loginModal.style.display = "none";
                registerModal.style.display = "none";
            }
        });

        window.onclick = (event) => {
            if (event.target == loginModal) {
                loginModal.style.display = "none";
            }
            if (event.target == registerModal) {
                registerModal.style.display = "none";
            }
        };

        document.getElementById("loginForm").onsubmit = (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);

            fetch("src/auth/login.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                if (data === "success") {
                    window.location.href = "index.php";
                } else {
                    document.getElementById("login_response").innerHTML = data;
                }
            })
            .catch(error => console.error("Error:", error));
        };

        document.getElementById("registerForm").onsubmit = (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);

            fetch("src/auth/register.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                if (data === "success") {
                    window.location.href = "index.php";
                } else {
                    document.getElementById("register_response").innerHTML = data;
                }
            })
            .catch(error => console.error("Error:", error));
        };
    });
    </script>
</body>
</html>