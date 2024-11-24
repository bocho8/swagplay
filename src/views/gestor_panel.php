<?php
include '../api/verificar_sesion.php';
session_start();
verificarPermisosGestor();
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
    <header>
        <nav class="container">
            <div class="logo">
                <img src="public/assets/images/SIMPLETRANSPARENTE.PNG" width="50" height="50" />
                <a href="../../index.php">SwagPlay</a>
            </div>
            <div class="nav-links">
                <a href="#peliculas">Películas</a>
                <a href="#categorias">Categorías</a>
                <div class="auth-buttons">
                    <button id="logoutBtn">Cerrar Sesión</button>
                </div>
            </div>
        </nav>
    </header>

    <div id="notification" class="notification"></div>

    <main class="container">
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
                        <th>Categorías</th>
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
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2024 SwagPlay. Todos los derechos reservados.</p>
        </div>
    </footer>
    <script src="../js/admin.js"></script>
</body>

</html>