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
                <img src="${pelicula.foto}" alt="${pelicula.titulo}">
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