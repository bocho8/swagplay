document.addEventListener('DOMContentLoaded', () => {
    cargarUsuarios();
    cargarPeliculas();
    cargarCategorias();
    cargarPerfiles();
    cargarSuscripciones();
    cargarVisualizaciones();

    // Event listeners para botones de agregar
    document.getElementById('addUserBtn').addEventListener('click', agregarUsuario);
    document.getElementById('addMovieBtn').addEventListener('click', agregarPelicula);
    document.getElementById('addCategoryBtn').addEventListener('click', agregarCategoria);
    document.getElementById('addProfileBtn').addEventListener('click', agregarPerfil);
    document.getElementById('addSubscriptionBtn').addEventListener('click', agregarSuscripcion);
});

// Función para mostrar notificaciones
function showNotification(message, isError = false) {
    const notification = document.getElementById('notification');
    notification.textContent = message;
    notification.className = 'notification' + (isError ? ' error' : ' show');
    setTimeout(() => {
        notification.classList.remove('show');
    }, 3000);
}

// Funciones para cargar datos
function cargarUsuarios() {
    fetch('api/usuarios.php')
    .then(res => res.json())
    .then(data => {
        const tbody = document.getElementById('usuariosTabla');
        tbody.innerHTML = data.usuarios.map(usuario => `
            <tr>
                <td>${usuario.email}</td>
                <td>${usuario.telefono}</td>
                <td>${usuario.cuidad}</td>
                <td>${usuario.pais}</td>
                <td>
                    <button onclick="editarUsuario('${usuario.email}')">Editar</button>
                    <button onclick="eliminarUsuario('${usuario.email}')">Eliminar</button>
                </td>
            </tr>
        `).join('');
    })
    .catch(err => showNotification('Error al cargar usuarios', true));
}

// Función para agregar usuario
function agregarUsuario() {
    const usuario = {
        email: prompt('Email:'),
        contrasena: prompt('Contraseña:'),
        telefono: prompt('Teléfono:'),
        cuidad: prompt('Ciudad:'),
        pais: prompt('País:')
    };

    if (usuario.email && usuario.contrasena && usuario.telefono) {
        fetch('api/usuarios.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(usuario)
        })
        .then(res => res.json())
        .then(() => {
            cargarUsuarios();
            showNotification('Usuario agregado exitosamente');
        })
        .catch(err => showNotification('Error al agregar usuario', true));
    }
}

// Función para editar usuario
function editarUsuario(email) {
    const usuario = {
        email: email,
        telefono: prompt('Nuevo teléfono:'),
        cuidad: prompt('Nueva ciudad:'),
        pais: prompt('Nuevo país:')
    };

    if (usuario.telefono && usuario.cuidad && usuario.pais) {
        fetch('api/usuarios.php', {
            method: 'PUT',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(usuario)
        })
        .then(res => res.json())
        .then(() => {
            cargarUsuarios();
            showNotification('Usuario actualizado exitosamente');
        })
        .catch(err => showNotification('Error al actualizar usuario', true));
    }
}

// Función para eliminar usuario
function eliminarUsuario(email) {
    if (confirm('¿Está seguro de eliminar este usuario?')) {
        fetch(`api/usuarios.php?email=${email}`, {
            method: 'DELETE'
        })
        .then(res => res.json())
        .then(() => {
            cargarUsuarios();
            showNotification('Usuario eliminado exitosamente');
        })
        .catch(err => showNotification('Error al eliminar usuario', true));
    }
}

// Funciones similares para Peliculas, Categorías, Perfiles, Suscripciones y Visualizaciones
function cargarPeliculas() {
    fetch('api/peliculas.php')
    .then(res => res.json())
    .then(data => {
        const tbody = document.getElementById('peliculasTabla');
        tbody.innerHTML = data.peliculas.map(pelicula => `
            <tr>
                <td>${pelicula.titulo}</td>
                <td>${pelicula.descripcion}</td>
                <td>${pelicula.calificacion_usuarios}</td>
                <td>${pelicula.lanzamiento}</td>
                <td>
                    <button onclick="editarPelicula(${pelicula.id_pelicula})">Editar</button>
                    <button onclick="eliminarPelicula(${pelicula.id_pelicula})">Eliminar</button>
                </td>
            </tr>
        `).join('');
    })
    .catch(err => showNotification('Error al cargar películas', true));
}

// Función para agregar película
function agregarPelicula() {
    const pelicula = {
        titulo: prompt('Título de la película:'),
        descripcion: prompt('Descripción:'),
        calificacion_usuarios: parseFloat(prompt('Calificación de usuarios:')),
        foto: prompt('Ruta de la foto:'),
        lanzamiento: prompt('Fecha de lanzamiento (YYYY-MM-DD):')
    };

    if (pelicula.titulo && pelicula.descripcion && !isNaN(pelicula.calificacion_usuarios)) {
        fetch('api/peliculas.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(pelicula)
        })
        .then(res => res.json())
        .then(() => {
            cargarPeliculas();
            showNotification('Película agregada exitosamente');
        })
        .catch(err => showNotification('Error al agregar película', true));
    }
}

// Función para eliminar película
function eliminarPelicula(id_pelicula) {
    if (confirm('¿Está seguro de eliminar esta película?')) {
        fetch(`api/peliculas.php?id_pelicula=${id_pelicula}`, {
            method: 'DELETE'
        })
        .then(res => res.json())
        .then(() => {
            cargarPeliculas();
            showNotification('Película eliminada exitosamente');
        })
        .catch(err => showNotification('Error al eliminar película', true));
    }
}

// Función para cargar categorías
function cargarCategorias() {
    fetch('api/categorias.php')
    .then(res => res.json())
    .then(data => {
        const tbody = document.getElementById('categoriasTabla');
        tbody.innerHTML = data.categorias.map(categoria => `
            <tr>
                <td>${categoria.nombre}</td>
                <td>${categoria.descripcion}</td>
                <td>
                    <button onclick="editarCategoria(${categoria.id_categoria})">Editar</button>
                    <button onclick="eliminarCategoria(${categoria.id_categoria})">Eliminar</button>
                </td>
            </tr>
        `).join('');
    })
    .catch(err => showNotification('Error al cargar categorías', true));
}

// Función para agregar categoría
function agregarCategoria() {
    const categoria = {
        nombre: prompt('Nombre de la categoría:'),
        descripcion: prompt('Descripción:')
    };

    if (categoria.nombre && categoria.descripcion) {
        fetch('api/categorias.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(categoria)
        })
        .then(res => res.json())
        .then(() => {
            cargarCategorias();
            showNotification('Categoría agregada exitosamente');
        })
        .catch(err => showNotification('Error al agregar categoría', true));
    }
}

// Función para eliminar categoría
function eliminarCategoria(id_categoria) {
    if (confirm('¿Está seguro de eliminar esta categoría?')) {
        fetch(`api/categorias.php?id_categoria=${id_categoria}`, {
            method: 'DELETE'
        })
        .then(res => res.json())
        .then(() => {
            cargarCategorias();
            showNotification('Categoría eliminada exitosamente');
        })
        .catch(err => showNotification('Error al eliminar categoría', true));
    }
}

// Función para cargar perfiles
function cargarPerfiles() {
    fetch('api/perfiles.php')
    .then(res => res.json())
    .then(data => {
        const tbody = document.getElementById('perfilesTabla');
        tbody.innerHTML = data.perfiles.map(perfil => `
            <tr>
                <td>${perfil.nombre}</td>
                <td>${perfil.usuario}</td>
                <td>
                    <button onclick="editarPerfil(${perfil.id_perfil})">Editar</button>
                    <button onclick="eliminarPerfil(${perfil.id_perfil})">Eliminar</button>
                </td>
            </tr>
        `).join('');
    })
    .catch(err => showNotification('Error al cargar perfiles', true));
}

// Función para agregar perfil
function agregarPerfil() {
    const perfil = {
        nombre: prompt('Nombre del perfil:'),
        usuario: prompt('Usuario asociado:')
    };

    if (perfil.nombre && perfil.usuario) {
        fetch('api/perfiles.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(perfil)
        })
        .then(res => res.json())
        .then(() => {
            cargarPerfiles();
            showNotification('Perfil agregado exitosamente');
        })
        .catch(err => showNotification('Error al agregar perfil', true));
    }
}

// Función para eliminar perfil
function eliminarPerfil(id_perfil) {
    if (confirm('¿Está seguro de eliminar este perfil?')) {
        fetch(`api/perfiles.php?id_perfil=${id_perfil}`, {
            method: 'DELETE'
        })
        .then(res => res.json())
        .then(() => {
            cargarPerfiles();
            showNotification('Perfil eliminado exitosamente');
        })
        .catch(err => showNotification('Error al eliminar perfil', true));
    }
}

// Función para cargar suscripciones
function cargarSuscripciones() {
    fetch('api/suscripciones.php')
    .then(res => res.json())
    .then(data => {
        const tbody = document.getElementById('suscripcionesTabla');
        tbody.innerHTML = data.suscripciones.map(suscripcion => `
            <tr>
                <td>${suscripcion.usuario}</td>
                <td>${suscripcion.plan}</td>
                <td>${suscripcion.fecha_inicio}</td>
                <td>${suscripcion.fecha_fin}</td>
                <td>
                    <button onclick="editarSuscripcion(${suscripcion.id_suscripcion})">Editar</button>
                    <button onclick="eliminarSuscripcion(${suscripcion.id_suscripcion})">Eliminar</button>
                </td>
            </tr>
        `).join('');
    })
    .catch(err => showNotification('Error al cargar suscripciones', true));
}

// Función para agregar suscripción
function agregarSuscripcion() {
    const suscripcion = {
        usuario: prompt('Usuario:'),
        plan: prompt('Plan:'),
        fecha_inicio: prompt('Fecha de inicio (YYYY-MM-DD):'),
        fecha_fin: prompt('Fecha de fin (YYYY-MM-DD):')
    };

    if (suscripcion.usuario && suscripcion.plan && suscripcion.fecha_inicio && suscripcion.fecha_fin) {
        fetch('api/suscripciones.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(suscripcion)
        })
        .then(res => res.json())
        .then(() => {
            cargarSuscripciones();
            showNotification('Suscripción agregada exitosamente');
        })
        .catch(err => showNotification('Error al agregar suscripción', true));
    }
}

// Función para eliminar suscripción
function eliminarSuscripcion(id_suscripcion) {
    if (confirm('¿Está seguro de eliminar esta suscripción?')) {
        fetch(`api/suscripciones.php?id_suscripcion=${id_suscripcion}`, {
            method: 'DELETE'
        })
        .then(res => res.json())
        .then(() => {
            cargarSuscripciones();
            showNotification('Suscripción eliminada exitosamente');
        })
        .catch(err => showNotification('Error al eliminar suscripción', true));
    }
}

// Función para cargar visualizaciones
function cargarVisualizaciones() {
    fetch('api/visualizaciones.php')
    .then(res => res.json())
    .then(data => {
        const tbody = document.getElementById('visualizacionesTabla');
        tbody.innerHTML = data.visualizaciones.map(visualizacion => `
            <tr>
                <td>${visualizacion.usuario}</td>
                <td>${visualizacion.pelicula}</td>
                <td>${visualizacion.fecha}</td>
                <td>
                    <button onclick="editarVisualizacion(${visualizacion.id_visualizacion})">Editar</button>
                    <button onclick="eliminarVisualizacion(${visualizacion.id_visualizacion})">Eliminar</button>
                </td>
            </tr>
        `).join('');
    })
    .catch(err => showNotification('Error al cargar visualizaciones', true));
}

// Función para agregar visualización
function agregarVisualizacion() {
    const visualizacion = {
        usuario: prompt('Usuario:'),
        pelicula: prompt('Pelicula:'),
        fecha: prompt('Fecha de visualización (YYYY-MM-DD):')
    };

    if (visualizacion.usuario && visualizacion.pelicula && visualizacion.fecha) {
        fetch('api/visualizaciones.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(visualizacion)
        })
        .then(res => res.json())
        .then(() => {
            cargarVisualizaciones();
            showNotification('Visualización agregada exitosamente');
        })
        .catch(err => showNotification('Error al agregar visualización', true));
    }
}

// Función para eliminar visualización
function eliminarVisualizacion(id_visualizacion) {
    if (confirm('¿Está seguro de eliminar esta visualización?')) {
        fetch(`api/visualizaciones.php?id_visualizacion=${id_visualizacion}`, {
            method: 'DELETE'
        })
        .then(res => res.json())
        .then(() => {
            cargarVisualizaciones();
            showNotification('Visualización eliminada exitosamente');
        })
        .catch(err => showNotification('Error al eliminar visualización', true));
    }
}