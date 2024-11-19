<?php
session_start();

if (isset($_SESSION["email"]) && $_SESSION["is_logged_in"]){
    header("Location: src/views/panel_usuario.php");
    exit;
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
            <div class="logo">SwagPlay</div>
            <div class="nav-links">
                <a href="#home">Inicio</a>
                <a href="#movies">Películas</a>
                <div class="auth-buttons">
                    <button class="auth-button" id="loginBtn">Iniciar Sesión</button>
                    <button class="auth-button" id="registerBtn">Registrarse</button>
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
                <div class="content-grid">
                    <div class="content-card">
                        <img src="/placeholder.svg?height=300&width=200" alt="Odisea Cósmica">
                        <div class="content-info">
                            <h3>Odisea Cósmica</h3>
                            <p>Un viaje alucinante a través del espacio y el tiempo.</p>
                        </div>
                    </div>
                    <div class="content-card">
                        <img src="/placeholder.svg?height=300&width=200" alt="Noches de Neón">
                        <div class="content-info">
                            <h3>Noches de Neón</h3>
                            <p>Sumérgete en el vibrante inframundo de una ciudad futurista.</p>
                        </div>
                    </div>
                    <div class="content-card">
                        <img src="/placeholder.svg?height=300&width=200" alt="Salto Cuántico">
                        <div class="content-info">
                            <h3>Salto Cuántico</h3>
                            <p>Desentraña los misterios del reino cuántico.</p>
                        </div>
                    </div>
                    <div class="content-card">
                        <img src="/placeholder.svg?height=300&width=200" alt="Ecos de la Eternidad">
                        <div class="content-info">
                            <h3>Ecos de la Eternidad</h3>
                            <p>Una historia épica que abarca siglos y dimensiones.</p>
                        </div>
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
        }

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
        }

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
        }
    </script>
</body>
</html>