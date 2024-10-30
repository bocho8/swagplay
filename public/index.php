<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SwagPlay</title>
    <link rel="stylesheet" href="css/style.css" />

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" />
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="logo">
                <a href=""><img src="assets/images/logos/logoblanco.png" alt="logo" width="100px" height="100px" /></a>
            </div>
            <ul class="nav-list">
                <li class="home"><a href="">Inicio</a></li>
                <li><a href="">Peliculas</a></li>
                <li><a href="">Planes</a></li>
                <li><button class="btn" id="open_login">Ingresar</button></li>
                <li><button class="btn" id="open_register">Registrarse</button></li>
            </ul>
            <div class="hamburger">
                <div class="line"></div>
                <div class="line"></div>
                <div class="line"></div>
            </div>
        </nav>
    </header>

    <div id="login_modal" class="modal">
        <div class="modal-content">
            <span class="close" id="close_login">&times;</span>
            <h2>Login</h2>
            <form action="../src/auth/login.php" method="POST" id="login_form">
                <label for="login_email">Email:</label>
                <input type="email" name="email" id="login_email" required>
                <label for="login_contrasena">contrasena:</label>
                <input type="contrasena" name="contrasena" id="login_contrasena" minlength="8" required>
                <button type="submit">ingresar</button>
            </form>
        </div>
    </div>

    <div id="register_modal" class="modal">
        <div class="modal-content">
            <span class="close" id="close_register">&times;</span>
            <h2>Registrarse</h2>
            <form action="../src/auth/register.php" method="POST" id="register_form">
                <label for="register_nombre">Nombre Completo:</label>
                <input type="text" name="nombre" id="register_nombre" required>
                <label for="register_email">Email:</label>
                <input type="email" name="email" id="register_email" required>
                <label for="register_contrasena">Contrasena:</label>
                <input type="contrasena" name="contrasena" id="register_contrasena" minlength="8" required>
                <label for="register_telefono">Telefono:</label>
                <input type="tel" name="telefono" id="register_telefono" required>
                <button type="submit">Registrarse</button>
            </form>
        </div>
    </div>

    <section class="hero">
      <div class="main">
        <div class="content">
          <p>SwagPlay</p>
          <h1>Peliculas <strong>Ilimitadas</strong>, & More.</h1>
          <div class="meta-wrapper">
    </section>

    <footer>
      <div class="main">
        <div class="footer-top">
          <div class="links">
            <div class="text-links">
              <a href="">faq</a>
              <a href="">centro de ayuda</a>
              <a href="">TOS</a>
              <a href="">privacidad</a>
            </div>
            <div class="social-links">
              <a href=""><i class="fa-brands fa-facebook"></i></a>
              <a href=""><i class="fa-brands fa-twitter"></i></a>
              <a href=""><i class="fa-brands fa-pinterest"></i></a>
              <a href=""><i class="fa-brands fa-linkedin"></i></a>
            </div>
          </div>
        </div>
        <div class="footer-bottom">
          <div class="copyright">
            <p>&#169; 2024 <a href="">SwagPlay</a>. Todos los derechos reservados</p>
          </div>
          <div class="img">
            <img src="assets/images/backgrounds/footer-bottom-img.png" alt="">
          </div>
        </div>
      </div>
    </footer>

    <button class="back-to-top"><i class="fa-solid fa-chevron-up"></i></button>

    <!-- <script src="js/script.js"></script> -->
    <script src="js/navbar.js"></script>
    <script src="js/login.js"></script>
  </body>
</html>
