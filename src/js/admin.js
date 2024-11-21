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
    
    notification.className = `notification ${isError ? 'error' : ''} show`;
    
    setTimeout(() => {
        notification.classList.remove('show');
    }, 10000);
}

document.getElementById('logoutBtn').addEventListener('click', () => {
    fetch('../auth/logout.php', { method: 'POST' })
    .then(() => {
        window.location.href = '../../index.php';
    });
    console.log('hola')
});


///////////////////////////////////////////////////////////////////////////////////////////////

// Funciones para cargar datos
function cargarUsuarios() {
    fetch('../api/usuarios.php')
    .then(res => res.json())
    .then(data => {
        const tbody = document.getElementById('usuariosTabla');
        tbody.innerHTML = data.usuarios.map(usuario => `
            <tr data-email="${usuario.email}">
                <td>${usuario.email}</td>
                <td class="telefono">${usuario.telefono}</td>
                <td class="ciudad">${usuario.cuidad}</td>
                <td class="pais">${usuario.pais}</td>
                <td>
                    <button onclick="editarUsuario('${usuario.email}')">Editar</button>
                    <button onclick="eliminarUsuario('${usuario.email}')">Eliminar</button>
                </td>
            </tr>
        `).join('');
    })
    .catch(err => showNotification('Error al cargar usuarios: ' + err, true));
}

// Función para agregar usuario
function agregarUsuario() {
    // Obtener los valores del formulario
    const email = document.getElementById('email').value;
    const contrasena = document.getElementById('contrasena').value;
    const telefono = document.getElementById('telefono').value || 0x000000000;
    const ciudad = document.getElementById('ciudad').value || 'null';
    const pais = document.getElementById('pais').value || 'null';

    // Validar que los campos requeridos no estén vacíos
    if (email && contrasena) {
        const usuario = { email, contrasena, telefono, ciudad, pais };

        // Hacer la solicitud para agregar el usuario
        fetch('../api/usuarios.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(usuario),
        })
        .then(res => res.json())
        .then(() => {
            cargarUsuarios();
            showNotification('Usuario agregado exitosamente');
            // Limpiar el formulario
            document.getElementById('usuarioForm').reset();
        })
        .catch(err => showNotification('Error al agregar usuario: ' + err, true));
    } else {
        showNotification('Por favor, completa todos los campos requeridos', true);
    }
}

// Función para editar usuario
function editarUsuario(email) {
    // Obtener datos existentes del usuario (simulado para este caso)
    const fila = document.querySelector(`[data-email="${email}"]`);
    const telefonoActual = fila.querySelector('.telefono').textContent;
    const ciudadActual = fila.querySelector('.ciudad').textContent;
    const paisActual = fila.querySelector('.pais').textContent;

    // Mostrar formulario de edición con los datos actuales
    const form = document.getElementById('editarUsuarioForm');
    form.style.display = 'block';

    document.getElementById('editEmail').value = email;
    document.getElementById('editTelefono').value = telefonoActual;
    document.getElementById('editCiudad').value = ciudadActual || null;
    document.getElementById('editPais').value = paisActual || null;

    // Evento para guardar cambios
    document.getElementById('saveEditBtn').onclick = () => {
        const usuario = {
            email: email,
            telefono: document.getElementById('editTelefono').value,
            cuidad: document.getElementById('editCiudad').value,
            pais: document.getElementById('editPais').value
        };

        fetch('../api/usuarios.php', {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(usuario)
        })
        .then(res => res.json())
        .then(() => {
            cargarUsuarios();
            showNotification('Usuario actualizado exitosamente');
            form.style.display = 'none'; // Ocultar formulario
        })
        .catch(err => showNotification('Error al actualizar usuario', true));
    };

    // Evento para cancelar edición
    document.getElementById('cancelEditBtn').onclick = () => {
        form.style.display = 'none';
    };
}


// Función para eliminar usuario
function eliminarUsuario(email) {
    const confirmacion = window.confirm('¿Está seguro de eliminar este usuario?');

    if (confirmacion) {
        fetch(`../api/usuarios.php?email=${email}`, {
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

///////////////////////////////////////////////////////////////////////////////////////////////

function cargarPeliculas() {
    Promise.all([
        fetch('../api/peliculas.php').then(res => res.json()),
        fetch('../api/categorias.php').then(res => res.json())
    ])
    .then(([dataPeliculas, dataCategorias]) => {
        // Cargar tabla de películas
        const tbody = document.getElementById('peliculasTabla');
        tbody.innerHTML = dataPeliculas.peliculas.map(pelicula => {
            return `
                <tr>
                    <td>${pelicula.id_pelicula}</td>
                    <td>${pelicula.titulo}</td>
                    <td>${pelicula.descripcion}</td>
                    <td>${pelicula.calificacion_usuarios}</td>
                    <td><img src="${pelicula.foto}" alt="${pelicula.titulo}" title="${pelicula.foto}" width="50" height="50"></td>
                    <td>${pelicula.lanzamiento}</td>
                    <td>${pelicula.categorias ? pelicula.categorias.map(cat => `<span class="categoria-badge">${cat.nombre}</span>`).join(' ') : 'Sin categorías'}</td>
                    <td>
                        <button onclick="editarPelicula(${pelicula.id_pelicula})">Editar</button>
                        <button onclick="eliminarPelicula(${pelicula.id_pelicula})">Eliminar</button>
                    </td>
                </tr>
            `;
        }).join('');

        // Cargar tabla de categorías
        const tbodyCategorias = document.getElementById('categoriasTabla');
        tbodyCategorias.innerHTML = dataCategorias.categorias.map(categoria => `
            <tr>
                <td>${categoria.id_categoria}</td>
                <td>${categoria.categoria}</td>
                <td>
                    <button onclick="editarCategoria(${categoria.id_categoria})">Editar</button>
                    <button onclick="eliminarCategoria(${categoria.id_categoria})">Eliminar</button>
                </td>
            </tr>
        `).join('');

        // Cargar select de categorías en el formulario de películas
        const selectCategorias = document.getElementById('categorias');
        selectCategorias.innerHTML = dataCategorias.categorias.map(categoria => `
            <option value="${categoria.id_categoria}">${categoria.categoria}</option>
        `).join('');

        // Guardar las categorías en una variable global para usarlas después
        window.categorias = dataCategorias.categorias;
    })
    .catch(err => showNotification('Error al cargar datos: ' + err, true));
}


// Función para agregar película
function agregarPelicula() {
    const titulo = document.getElementById('titulo').value
    const descripcion = document.getElementById('descripcion').value
    const calificacion_usuarios = document.getElementById('calificacion').value
    const foto = document.getElementById('foto').value
    const lanzamiento = document.getElementById('lanzamiento').value
    const categorias = Array.from(document.getElementById('categorias').selectedOptions).map(option => option.value); //gracias chat no tenia ni idea

    if (titulo && descripcion && !isNaN(calificacion_usuarios)) {
        const pelicula = { titulo, descripcion, calificacion_usuarios, foto, lanzamiento, categorias }
        fetch('../api/peliculas.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(pelicula)
        })
        .then(res => res.json())
        .then(() => {
            cargarPeliculas();
            showNotification('Película agregada exitosamente');
            document.getElementById('peliculaForm').reset();
        })
        .catch(err => showNotification('Error al agregar película: ' + err, true));
    } else {
        showNotification('Por favor, completa todos los campos requeridos', true);
    }
}

// Función para editar película
function editarPelicula(id_pelicula) {
    const fila = document.querySelector(`[data-id_pelicula="${id_pelicula}"]`);
    const tituloActual = fila.querySelector('td:nth-child(2)').textContent;
    const descripcionActual = fila.querySelector('td:nth-child(3)').textContent;
    const calificacionActual = fila.querySelector('td:nth-child(4)').textContent;
    const fotoActual = fila.querySelector('td:nth-child(5) img').src;
    const lanzamientoActual = fila.querySelector('td:nth-child(6)').textContent;
    const categoriasActual = fila.querySelector('td:nth-child(7)').textContent.trim();

    // Mostrar formulario de edición con los datos actuales
    const form = document.getElementById('editarPeliculaForm');
    form.style.display = 'block';

    document.getElementById('editTitulo').value = tituloActual;
    document.getElementById('editDescripcion').value = descripcionActual;
    document.getElementById('editCalificacion').value = calificacionActual;
    document.getElementById('editFoto').value = fotoActual;
    document.getElementById('editLanzamiento').value = lanzamientoActual;

    // Cargar categorías en el select de edición
    const selectEditCategorias = document.getElementById('editCategorias');
    selectEditCategorias.innerHTML = window.categorias.map(categoria => {
        const selected = categoriasActual.includes(categoria.categoria) ? 'selected' : '';
        return `<option value="${categoria.id_categoria}" ${selected}>${categoria.categoria}</option>`;
    }).join('');

    // Evento para guardar cambios
    document.getElementById('saveEditMovieBtn').onclick = () => {
        const editPelicula = {
            id_pelicula,
            descripcion: document.getElementById('editDescripcion').value,
            calificacion_usuarios: document.getElementById('editCalificacion').value,
            foto: document.getElementById('editFoto').value,
            lanzamiento: document.getElementById('editLanzamiento').value,
            categorias: Array.from(document.getElementById('editCategorias').selectedOptions).map(option => option.value)
        };

        fetch('../api/peliculas.php', {
            method: 'PUT',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(editPelicula)
        })
        .then(res => res.json())
        .then(() => {
            cargarPeliculas();
            showNotification('Película actualizada exitosamente');
            form.style.display = 'none'; // Ocultar formulario
        })
        .catch(err => showNotification('Error al actualizar película: ' + err, true));
    };

    // Evento para cancelar edición
    document.getElementById('cancelEditMovieBtn').onclick = () => {
        form.style.display = 'none';
    };
}

// Función para eliminar película
function eliminarPelicula(id_pelicula) {
    if (confirm('¿Está seguro de eliminar esta película?')) {
        fetch(`../api/peliculas.php?id_pelicula=${id_pelicula}`, {
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

///////////////////////////////////////////////////////////////////////////////////////////////

// Función para cargar categorías
function cargarCategorias() {
    fetch('../api/categorias.php')
    .then(res => res.json())
    .then(data => {
        const tbody = document.getElementById('categoriasTabla');
        tbody.innerHTML = data.categorias.map(categoria => `
            <tr>
                <td>${categoria.id_categoria}</td>
                <td>${categoria.categoria}</td>
                <td>
                    <button onclick="editarCategoria(${categoria.id_categoria})">Editar</button>
                    <button onclick="eliminarCategoria(${categoria.id_categoria})">Eliminar</button>
                </td>
            </tr>
        `).join('');
    })
    .catch(err => showNotification('Error al cargar categorías: ' + err, true));
}

// Función para agregar categoría
function agregarCategoria() {

    const categoria = document.getElementById('categoria').value

    if (categoria) {
        fetch('../api/categorias.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({categoria})
        })
        .then(res => res.json())
        .then(() => {
            cargarCategorias();
            showNotification('Categoría agregada exitosamente');
        })
        .catch(err => showNotification('Error al agregar categoría: ' + err, true));
    } else {
        showNotification('Por favor, completa todos los campos requeridos', true);
    }
}

// Función para eliminar categoría
function eliminarCategoria(id_pelicula) {
    if (confirm('¿Está seguro de eliminar esta categoría?')) {
        fetch(`../api/categorias.php?id_pelicula=${id_pelicula}`, {
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

///////////////////////////////////////////////////////////////////////////////////////////////

// Función para cargar perfiles
function cargarPerfiles() {
    fetch('../api/perfiles.php')
    .then(res => res.json())
    .then(data => {
        const tbody = document.getElementById('perfilesTabla');
        tbody.innerHTML = data.perfiles.map(perfil => `
            <tr>
                <td>${perfil.nombrePerfil}</td>
                <td>${perfil.emailPerfil}</td>
                <td>
                    <button onclick="editarPerfil(${perfil.id_perfil})">Editar</button>
                    <button onclick="eliminarPerfil(${perfil.id_perfil})">Eliminar</button>
                </td>
            </tr>
        `).join('');
    })
    .catch(err => showNotification('Error al cargar perfiles: ' + err, true));
}

// Función para agregar perfil
function agregarPerfil() {
    const perfil = {
        nombre: document.getElementById('nombrePerfil').value,
        email: document.getElementById('emailPerfil').value
    };

    if (perfil.nombre && perfil.email) {
        fetch('../api/perfiles.php', {
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
    } else {
        showNotification('Por favor, completa todos los campos requeridos', true);
    }
}

// Función para eliminar perfil
function eliminarPerfil(id_perfil) {
    if (confirm('¿Está seguro de eliminar este perfil?')) {
        fetch(`../api/perfiles.php?id_perfil=${id_perfil}`, {
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

///////////////////////////////////////////////////////////////////////////////////////////////

// Función para cargar suscripciones
function cargarSuscripciones() {
    fetch('../api/suscripciones.php')
    .then(res => res.json())
    .then(data => {
        const tbody = document.getElementById('suscripcionesTabla');
        tbody.innerHTML = data.suscripciones.map(suscripcion => `
            <tr>
                <td>${suscripcion.pantallasSimultaneas}</td>
                <td>${suscripcion.nombreSuscripcion}</td>
                <td>${suscripcion.emailSuscripcion}</td>
                <td>
                    <button onclick="editarSuscripcion(${suscripcion.id_suscripcion})">Editar</button>
                    <button onclick="eliminarSuscripcion(${suscripcion.id_suscripcion})">Eliminar</button>
                </td>
            </tr>
        `).join('');
    })
    .catch(err => showNotification('Error al cargar suscripciones: ' + err, true));
}

// Función para agregar suscripción
function agregarSuscripcion() {
    const suscripcion = {
        pantallas: document.getElementById('pantallasSimultaneas').value,
        nombre: document.getElementById('nombreSuscripcion').value,
        email: document.getElementById('emailSuscripcion').value
    };

    if (suscripcion.pantallas && suscripcion.nombre && suscripcion.email) {
        fetch('../api/suscripciones.php', {
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
    } else {
        showNotification('Por favor, completa todos los campos requeridos', true);
    }
}

// Función para eliminar suscripción
function eliminarSuscripcion(email) {
    if (confirm('¿Está seguro de eliminar esta suscripción?')) {
        fetch(`../api/suscripciones.php?email=${email}`, {
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

///////////////////////////////////////////////////////////////////////////////////////////////

// Función para cargar visualiza
function cargarVisualizaciones() {
    fetch('../api/visualiza.php')
    .then(res => res.json())
    .then(data => {
        const tbody = document.getElementById('visualizacionesTabla');
        tbody.innerHTML = data.visualiza.map(visualizacion => `
            <tr>
                <td>${visualizacion.emailVisualizacion}</td>
                <td>${visualizacion.idPeliculaVisualizacion}</td>
                <td>${visualizacion.calificacionVisualizacion}</td>
                <td>${visualizacion.segundoPelicula}</td>
                <td>
                    <button onclick="editarVisualizacion(${visualizacion.id_visualizacion})">Editar</button>
                    <button onclick="eliminarVisualizacion(${visualizacion.id_visualizacion})">Eliminar</button>
                </td>
            </tr>
        `).join('');
    })
    .catch(err => showNotification('Error al cargar visualiza: ' + err, true));
}

// Función para agregar visualización
function agregarVisualizacion() {
    const visualizacion = {
        email: document.getElementById('emailVisualizacion').value,
        idPelicula: document.getElementById('idPeliculaVisualizacion').value,
        califcacion: document.getElementById('calificacionVisualizacion').value,
        segundoPelicula: document.getElementById('segundoPelicula').value
    };

    if (visualizacion.email && visualizacion.idPelicula && visualizacion.califcacion && visualizacion.segundoPelicula) {
        fetch('../api/visualiza.php', {
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
    } else {
        showNotification('Por favor, completa todos los campos requeridos', true);
    }
}

// Función para eliminar visualización
function eliminarVisualizacion(email, id_pelicula) {
    if (confirm('¿Está seguro de eliminar esta visualización?')) {
        fetch(`../api/visualiza.php?email=${email}&id_pelicula=${id_pelicula}`, {
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