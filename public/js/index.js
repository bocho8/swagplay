document.addEventListener('DOMContentLoaded', () => {
    cargarPeliculas();
    configurarInteraccionesUI();
    configurarFormularios();
    configurarRedireccionLoginConModal();
    cargarDatosUsuario();
});

function cargarPeliculas() {
    fetch('src/api/peliculas.php')
        .then(respuesta => {
            if (!respuesta.ok) throw new Error(`Error en la API: ${respuesta.statusText}`);
            return respuesta.json();
        })
        .then(datos => {
            const grid = document.getElementById('content-grid');
            const peliculasAMostrar = datos.peliculas.slice(0, 3);
            grid.innerHTML = peliculasAMostrar.length
                ? peliculasAMostrar.map(pelicula => crearTarjetaPelicula(pelicula)).join('')
                : '<p>No hay películas disponibles.</p>';
        })
        .catch(error => {
            console.error('Error al obtener películas:', error);
            document.getElementById('content-grid').innerHTML = '<p>Error al cargar las películas.</p>';
        });
}

function crearTarjetaPelicula(pelicula) {
    return `
        <div class="content-card">
            <img src="${pelicula.foto}" alt="${pelicula.titulo}">
            <div class="content-info">
                <h3>${pelicula.titulo}</h3>
                <p>${pelicula.descripcion}</p>
                <p>Calificación: ${pelicula.calificacion_usuarios == 0 ? 'N/A' : pelicula.calificacion_usuarios || 'N/A'}</p>
                <p>Lanzamiento: ${pelicula.lanzamiento}</p>
            </div>
        </div>
    `;
}

function configurarInteraccionesUI() {
    document.querySelectorAll('a[href^="#"]').forEach(enlace => {
        enlace.addEventListener('click', function (evento) {
            evento.preventDefault();
            document.querySelector(this.getAttribute('href')).scrollIntoView({ behavior: 'smooth' });
        });
    });

    window.addEventListener('scroll', () => {
        const header = document.querySelector('header');
        header.style.backgroundColor = window.scrollY > 50 ? 'rgba(10, 10, 10, 0.95)' : 'rgba(10, 10, 10, 0.8)';
    });

    const botonSalir = document.getElementById('logoutBtn');
    if (botonSalir) {
        botonSalir.addEventListener('click', () => {
            fetch('src/auth/logout.php', { method: 'POST' })
                .then(() => window.location.href = 'index.php');
        });
    }
}

function configurarFormularios() {
    configurarFormulario('loginForm', 'src/auth/login.php', 'login_response');
    configurarFormulario('registerForm', 'src/auth/register.php', 'register_response');
    configurarModales();
}

function configurarFormulario(idFormulario, url, idRespuesta) {
    const formulario = document.getElementById(idFormulario);
    if (formulario) {
        formulario.onsubmit = (evento) => {
            evento.preventDefault();
            const datosFormulario = new FormData(evento.target);

            fetch(url, { method: 'POST', body: datosFormulario })
                .then(respuesta => respuesta.text())
                .then(datos => {
                    if (datos === "success") {
                        window.location.href = "index.php";
                    } else {
                        document.getElementById(idRespuesta).innerHTML = datos;
                    }
                })
                .catch(error => console.error('Error:', error));
        };
    }
}

function configurarModales() {
    const loginBtn = document.getElementById("loginBtn");
    const registerBtn = document.getElementById("registerBtn");
    const loginModal = document.getElementById("loginModal");
    const registerModal = document.getElementById("registerModal");
    const botonesCerrar = document.getElementsByClassName("close");

    if (loginBtn) loginBtn.onclick = () => loginModal.style.display = "block";
    if (registerBtn) registerBtn.onclick = () => registerModal.style.display = "block";

    Array.from(botonesCerrar).forEach(boton => {
        boton.onclick = () => {
            loginModal.style.display = "none";
            registerModal.style.display = "none";
        };
    });

    window.onclick = (evento) => {
        if (evento.target === loginModal) loginModal.style.display = "none";
        if (evento.target === registerModal) registerModal.style.display = "none";
    };
}

function configurarRedireccionLoginConModal(botton_class) {
    const botton = document.querySelector('.' + botton_class);
    const loginModal = document.getElementById('loginModal');

    if (botton && botton.getAttribute('href') === '#') {
        botton.addEventListener('click', (evento) => {
            evento.preventDefault();
            if (loginModal) {
                loginModal.style.display = 'block';
            }
        });
    }
}

configurarRedireccionLoginConModal("ic-button");
configurarRedireccionLoginConModal("pa-button");
configurarRedireccionLoginConModal("cta-button");


function cargarDatosUsuario() {
    fetch('src/auth/get_user_data.php')
        .then(response => {
            if (response == 'no_session')
                return;
            
            return response.json();
        })
        .then(user => {
            const profile = document.getElementById('user-profile');
            profile.innerHTML = '<a href="src/views/usuario_panel.php" class="user-avatar" id="userAvatar">U</a>';
            document.getElementById('userAvatar').textContent = user.name[0].toUpperCase();
        })
        .catch(error => { console.error('Error:', error) });
}
