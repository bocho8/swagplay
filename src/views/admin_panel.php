<?php

session_start();
include '../api/verificar_sesion.php';

if (!isset($_SESSION['email']) || $_SESSION['email'] !== 'admin@swagplay.com') {
    header("Location: ../../index.php"); // Redirige al usuario no autorizado
    exit();
}
verificarPermisosAdmin(); // por las dudas
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SwagPlay - Panel de Administración</title>
    <link rel="stylesheet" href="../../css/admin_panel.css">
</head>

<body>

    <div id="notification" class="notification"></div>

    <header>
        <nav class="container">
            <div class="logo">
                <img src="../../assets/images/SIMPLETRANSPARENTE.PNG" width="50" height="50"/>
                <a href="../../index.php">SwagPlay</a>
            </div>
            <div class="nav-links">
                <a href="#usuarios">Usuarios</a>
                <a href="#peliculas">Películas</a>
                <a href="#categorias">Categorías</a>
                <a href="#suscripciones">Suscripciones</a>
                <a href="#visualizaciones">Visualizaciones</a>
                <div class="auth-buttons">
                    <button id="logoutBtn">Cerrar Sesión</button>
                </div>
            </div>
        </nav>
    </header>

    <main class="container">
        <!-- Sección de Usuarios -->
        <section id="usuarios" class="section">
            <h2>Gestión de Usuarios</h2>
            <form id="usuarioForm">
                <h3>Agregar Usuario</h3>
                <label for="email">Email:</label>
                <input type="email" id="email" required>
                <label for="contrasena">Contraseña:</label>
                <input type="password" id="contrasena" required>
                <label for="telefono">Teléfono:</label>
                <input type="text" id="telefono" required>
                <label for="ciudad">Ciudad:</label>
                <input type="text" id="ciudad">
                <label for="pais">País:</label>
                <input type="text" id="pais">
                <button type="button" id="addUserBtn" class="btn-add">Agregar Usuario</button>
            </form>
            <form id="editarUsuarioForm" style="display: none;">
                <h3>Editar Usuario</h3>
                <label for="editEmailUsuario">Email (no editable):</label>
                <input type="email" id="editEmailUsuario" readonly>
                <label for="editContrasena">Nueva Contraseña:</label>
                <input type="password" id="editContrasena">
                <label for="editTelefono">Nuevo Teléfono:</label>
                <input type="text" id="editTelefono" required>
                <label for="editCiudad">Nueva Ciudad:</label>
                <input type="text" id="editCiudad">
                <label for="editPais">Nuevo País:</label>
                <input type="text" id="editPais">
                <button type="button" id="saveEditBtn">Guardar Cambios</button>
                <button type="button" id="cancelEditBtn">Cancelar</button>
            </form>
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
            <form id="peliculaForm">
                <h3>Agregar Película</h3>
                <label for="titulo">Título:</label>
                <input type="text" id="titulo" required>
                <label for="descripcion">Descripción:</label>
                <textarea id="descripcion" required></textarea>
                <label for="calificacion">Calificación:</label>
                <input type="number" id="calificacion" min="0" max="10" step="0.1" required>
                <label for="foto">Foto (URL):</label>
                <input type="url" id="foto" required>
                <label for="lanzamiento">Fecha de Lanzamiento:</label>
                <input type="date" id="lanzamiento" required>
                <label for="categorias">Categorías:</label>
                <select id="categorias" name="categorias" multiple></select>
                <button type="button" id="addMovieBtn" class="btn-add">Agregar Película</button>
            </form>
            <form id="editarPeliculaForm" style="display: none;">
                <h3>Editar Película</h3>
                <label for="editTitulo">Título (no editable):</label>
                <input type="text" id="editTitulo" readonly>
                <label for="editDescripcion">Nueva Descripción:</label>
                <textarea id="editDescripcion" required></textarea>
                <label for="editCalificacion">Nueva Calificación:</label>
                <input type="number" id="editCalificacion" min="0" max="10" step="0.1" required>
                <label for="editFoto">Nueva Foto (URL):</label>
                <input type="url" id="editFoto">
                <label for="editLanzamiento">Nueva Fecha de Lanzamiento:</label>
                <input type="date" id="editLanzamiento">
                <label for="editCategorias">Nuevas Categorías:</label>
                <select id="editCategorias" name="editCategorias" multiple></select>
                <button type="button" id="saveEditMovieBtn">Guardar Cambios</button>
                <button type="button" id="cancelEditMovieBtn">Cancelar</button>
            </form>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Descripción</th>
                        <th>Calificación</th>
                        <th>Foto</th>
                        <th>Lanzamiento</th>
                        <th>Categorias</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="peliculasTabla"></tbody>
            </table>
        </section>


        <!-- Sección de Categorías -->
        <section id="categorias" class="section">
            <h2>Gestión de Categorías</h2>
            <form id="categoriaForm">
                <h3>Agregar Categoría</h3>
                <label for="categoria">Categoría:</label>
                <input type="text" id="categoria" required>
                <button type="button" id="addCategoryBtn" class="btn-add">Agregar Categoría</button>
            </form>
            <form id="editarCategoriaForm" style="display: none;">
                <h3>Editar Categoría</h3>
                <label for="editId">ID (no editable):</label>
                <input type="text" id="editId" readonly>
                <label for="editCategoria">Categoría:</label>
                <input type="text" id="editCategoria" required>
                <button type="button" id="saveEditCategoriaBtn">Guardar Cambios</button>
                <button type="button" id="cancelEditCategoriaBtn">Cancelar</button>
            </form>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Categoría</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="categoriasTabla"></tbody>
            </table>
        </section>

        <!-- Sección de Perfiles -->
        <!--
        <section id="perfiles" class="section">
            <h2>Gestión de Perfiles</h2>
            <form id="perfilForm">
                <h3>Agregar Perfil</h3>
                <label for="nombrePerfil">Nombre:</label>
                <input type="text" id="nombrePerfil" required>
                <label for="emailPerfil">Email:</label>
                <input type="email" id="emailPerfil" required>
                <button type="button" id="addProfileBtn" class="btn-add">Agregar Perfil</button>
            </form>
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
        </section> -->

        <!-- Sección de Suscripciones -->
        <section id="suscripciones" class="section">
            <h2>Gestión de Suscripciones</h2>
            <form id="suscripcionForm">
                <h3>Agregar Suscripción</h3>
                <label for="pantallasSimultaneas">Pantallas Simultáneas:</label>                
                <input type="number" id="pantallasSimultaneas" required>
                <!-- <label for="nombreSuscripcion">Nombre:</label>
                <input type="text" id="nombreSuscripcion" required> -->
                <label for="emailSuscripcion">Email:</label>
                <input type="email" id="emailSuscripcion" required>
                <button type="button" id="addSubscriptionBtn" class="btn-add">Agregar Suscripción</button>
            </form>
            <form id="editarSuscripcionForm" style="display: none;">
                <label for="editEmailSuscripcion">Email (no editable)</label>
                <input type="email" id="editEmailSuscripcion" readonly>
                <label for="editPantallasSimultaneas">Pantallas Simultáneas:</label>
                <input type="number" id="editPantallasSimultaneas">
                <button type="button" id="saveEditSuscripcionBtn">Guardar Cambios</button>
                <button type="button" id="cancelEditSuscripcionBtn">Cancelar</button>
            </form>
            <table>
                <thead>
                    <tr>
                        <th>Email</th>
                        <th>Pantallas Simultáneas</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody id="suscripcionesTabla"></tbody>
            </table>
        </section>


        <!-- Sección de Visualizaciones -->
        <section id="visualizaciones" class="section">
            <h2>Registro de Visualizaciones</h2>
            <form id="visualizacionForm">
                <h3>Registrar Visualización</h3>
                <label for="emailVisualizacion">Email:</label>
                <input type="email" id="emailVisualizacion" required>
                <label for="idPeliculaVisualizacion">ID Película:</label>
                <input type="number" id="idPeliculaVisualizacion" required>
                <label for="calificacionVisualizacion">Calificación:</label>
                <input type="number" id="calificacionVisualizacion" min="1" max="5" required>
                <label for="segundoPelicula">Segundo en Película:</label>
                <input type="number" id="segundoPelicula" required>
                <button type="button" id="addVisualizacionBtn" class="btn-add">Registrar Visualización</button>
            </form>
            <form id="editVisualizacionForm" style="display: none;">
                <h3>Editar Visualización</h3>
                <label for="editEmailVisualizacion">Email (no editable)</label>
                <input type="email" id="editEmailVisualizacion" readonly>
                <label for="editIdPeliculaVisualizacion">ID Película (no editable)</label>
                <input type="number" id="editIdPeliculaVisualizacion" readonly>
                <label for="editCalificacionVisualizacion">Calificación:</label>
                <input type="number" id="editCalificacionVisualizacion" min="1" max="5" required>
                <label for="editSegundoPelicula">Segundo en Película:</label>
                <input type="number" id="editSegundoPelicula" required>
                <button type="button" id="saveEditVisualizacionBtn">Guardar Cambios</button>
                <button type="button" id="cancelEditVisualizacionBtn">Cancelar</button>
            </form>
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