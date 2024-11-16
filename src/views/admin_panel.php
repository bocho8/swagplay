<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SwagPlay - Panel de Administración</title>
    <link rel="stylesheet" href="../../css/admin_panel.css">
</head>
<body>
    <header>
        <div class="container">
            <h1>SwagPlay - Panel de Administración</h1>
            <nav>
                <a href="#usuarios">Usuarios</a>
                <a href="#peliculas">Películas</a>
                <a href="#categorias">Categorías</a>
                <a href="#perfiles">Perfiles</a>
                <a href="#suscripciones">Suscripciones</a>
                <a href="#visualizaciones">Visualizaciones</a>
            </nav>
            <button id="logoutBtn">Cerrar Sesión</button>
        </div>
    </header>

    <div id="notification" class="notification"></div>

    <main class="container">
        <!-- Sección de Usuarios -->
        <section id="usuarios" class="section">
            <h2>Gestión de Usuarios</h2>
            <button id="addUserBtn" class="btn-add">Agregar Usuario</button>
            <table>
                <thead>
                    <tr>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th>Ciudad</th>
                        <th>País</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="usuariosTabla"></tbody>
            </table>
        </section>

        <!-- Sección de Películas -->
        <section id="peliculas" class="section">
            <h2>Gestión de Películas</h2>
            <button id="addMovieBtn" class="btn-add">Agregar Película</button>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Descripción</th>
                        <th>Calificación</th>
                        <th>Foto</th>
                        <th>Lanzamiento</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="peliculasTabla"></tbody>
            </table>
        </section>

        <!-- Sección de Categorías -->
        <section id="categorias" class="section">
            <h2>Gestión de Categorías</h2>
            <button id="addCategoryBtn" class="btn-add">Agregar Categoría</button>
            <table>
                <thead>
                    <tr>
                        <th>Categoría</th>
                        <th>ID Película</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="categoriasTabla"></tbody>
            </table>
        </section>

        <!-- Sección de Perfiles -->
        <section id="perfiles" class="section">
            <h2>Gestión de Perfiles</h2>
            <button id="addProfileBtn" class="btn-add">Agregar Perfil</button>
            <table>
                <thead>
                    <tr>
                        <th>ID Perfil</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="perfilesTabla"></tbody>
            </table>
        </section>

        <!-- Sección de Suscripciones -->
        <section id="suscripciones" class="section">
            <h2>Gestión de Suscripciones</h2>
            <button id="addSubscriptionBtn" class="btn-add">Agregar Suscripción</button>
            <table>
                <thead>
                    <tr>
                        <th>Pantallas Simultáneas</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="suscripcionesTabla"></tbody>
            </table>
        </section>

        <!-- Sección de Visualizaciones -->
        <section id="visualizaciones" class="section">
            <h2>Registro de Visualizaciones</h2>
            <table>
                <thead>
                    <tr>
                        <th>Email</th>
                        <th>ID Película</th>
                        <th>Calificación</th>
                        <th>Segundo Película</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="visualizacionesTabla"></tbody>
            </table>
        </section>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2024 SwagPlay. Todos los derechos reservados.</p>
        </div>
    </footer>
    <script src="../js/admin.js"></script>
</body>
</html>