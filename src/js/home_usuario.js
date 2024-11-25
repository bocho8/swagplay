let USER_EMAIL = null;

document.addEventListener('DOMContentLoaded', () => {
    setEmail();
    loadPeliculas();
    loadDatosUsuario();
});

function setEmail() {
    fetch('../api/getEmail.php')
        .then(response => {
            if (response.ok) {
                return response.text();
            } else {
                throw new Error('Error al obtener el email del usuario');
            }
        })
        .then(email => {
            USER_EMAIL = email.trim();
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

function loadPeliculas() {
    fetch('../api/peliculas.php')
        .then(response => response.json())
        .then(data => {
            const peliculas = data.peliculas;
            const moviesGrid = document.getElementById('moviesGrid');

            moviesGrid.innerHTML = ''; // Limpiar el grid antes de cargar

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
                peliculaCard.addEventListener('click', () => openModal(pelicula));
                moviesGrid.appendChild(peliculaCard);
            });
        })
        .catch(error => {
            console.error('Error al cargar las películas:', error);
        });
}

function loadDatosUsuario() {
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
}

// Manejar el cierre de sesión
document.getElementById('logoutBtn').addEventListener('click', () => {
    fetch('../auth/logout.php', {
        method: 'POST'
    })
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

function openModal(pelicula) {
    const modal = document.getElementById('movieModal');
    const modalTitle = document.getElementById('modalTitle');
    const modalImage = document.getElementById('modalImage');
    const modalDescription = document.getElementById('modalDescription');
    const modalCategories = document.getElementById('modalCategories');
    const modalYear = document.getElementById('modalYear');
    const modalRating = document.getElementById('modalRating');
    const modalWatchButton = document.getElementById('modalWatchButton');
    const saveRatingButton = document.getElementById('saveRatingButton');
    const ratingStars = document.getElementById('ratingStars');
    const userRatingDisplay = document.getElementById('userRating');

    modalTitle.textContent = pelicula.titulo;
    modalImage.src = pelicula.foto;
    modalDescription.textContent = pelicula.descripcion;
    modalYear.textContent = `Año de lanzamiento: ${pelicula.lanzamiento}`;
    modalRating.textContent = `Calificación: ${pelicula.calificacion_usuarios}/5`;

    if (pelicula.categorias.length > 0) {
        modalCategories.innerHTML = `<strong>Categorías:</strong> ${pelicula.categorias
            .map(categoria => `<span class="categoria-badge">${categoria.nombre}</span>`)
            .join(', ')}`;
    } else {
        modalCategories.innerHTML = `<strong>Categorías:</strong> Sin categorías asociadas`;
    }

    modalWatchButton.textContent = 'Ver Película';
    modalWatchButton.onclick = () => {
        fetch('../api/visualiza.php')
            .then(response => response.json())
            .then(data => {
                const visualizacion = data.visualiza.find(
                    v => v.id_pelicula === pelicula.id_pelicula && v.email === USER_EMAIL
                ) || false;

                let segundosIniciales = 0;

                if (!visualizacion) {
                    fetch('../api/visualiza.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            email: USER_EMAIL,
                            id_pelicula: pelicula.id_pelicula
                        })
                    })
                        .then(response => response.json())
                        .then(() => {
                            handleWatch(pelicula, 0);
                        })
                        .catch(error => {
                            console.error('Error al crear visualización:', error);
                            handleWatch(pelicula, 0);
                        });
                } else {
                    segundosIniciales = visualizacion.segundo_pelicula;
                    handleWatch(pelicula, segundosIniciales);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                handleWatch(pelicula, 0);
            });
    };

    fetch('../api/visualiza.php')
        .then(response => response.json())
        .then(data => {
            if (data.visualiza && data.visualiza.length > 0) {
                const visualiza = data.visualiza.find(v => v.id_pelicula === pelicula.id_pelicula && v.email === USER_EMAIL);
                console.log(visualiza);
                if (visualiza && visualiza.calificacion !== null && visualiza.calificacion !== undefined) {
                    userRatingDisplay.textContent = visualiza.calificacion;
                    setRatingStars(visualiza.calificacion);
                } else {
                    userRatingDisplay.textContent = 'Sin calificar';
                }
            } else {
                userRatingDisplay.textContent = 'No se ha encontrado calificación';
            }
        })
        .catch(error => {
            console.error('Error al obtener la calificación:', error);
        });

    ratingStars.querySelectorAll('.star').forEach(star => {
        star.addEventListener('click', () => {
            const rating = star.getAttribute('data-value');
            userRatingDisplay.textContent = rating;
            setRatingStars(rating);
        });
    });

    saveRatingButton.addEventListener('click', () => {
        const rating = userRatingDisplay.textContent;

        fetch('../api/visualiza.php', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                email: USER_EMAIL,
                id_pelicula: pelicula.id_pelicula,
                calificacion: rating
            })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateMovieAverageRating(pelicula.id_pelicula);
                }
            })
            .catch(error => {
                console.error('Error al guardar la calificación:', error);
            });
    });

    modal.style.display = 'block';
}

function setRatingStars(rating) {
    const stars = document.querySelectorAll('#ratingStars .star');
    stars.forEach(star => {
        if (star.getAttribute('data-value') <= rating) {
            star.classList.add('selected');
        } else {
            star.classList.remove('selected');
        }
    });
}

function handleWatch(pelicula, segundosIniciales) {
    let segundos = segundosIniciales;
    let timer;

    const modalContent = document.querySelector('.modal-content');
    const tiempoContainer = document.createElement('div');
    tiempoContainer.id = 'tiempoVisualizacion';
    tiempoContainer.style.marginTop = '10px';
    tiempoContainer.innerHTML = `Tiempo visualizado: ${segundos} segundos`;
    modalContent.appendChild(tiempoContainer);

    const modalWatchButton = document.getElementById('modalWatchButton');
    modalWatchButton.disabled = true;

    timer = setInterval(() => {
        segundos++;
        tiempoContainer.innerHTML = `Tiempo visualizado: ${segundos} segundos`;
        

        fetch('../api/visualiza.php', {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                email: USER_EMAIL,
                id_pelicula: pelicula.id_pelicula,
                segundo_pelicula: segundos
            })
        })
            .catch(error => {
                console.error('Error al actualizar tiempo:', error);
            });
    }, 1000);

    document.querySelector('.close').addEventListener('click', () => {
        clearInterval(timer);
    });

    window.addEventListener('click', (event) => {
        if (event.target === document.getElementById('movieModal')) {
            clearInterval(timer);
        }
    });
}

function updateMovieAverageRating(id_pelicula) {
    fetch(`../api/peliculas.php?id_pelicula=${id_pelicula}`)
        .then(response => response.json())
        .then(data => {
            const pelicula = data.peliculas.find(p => p.id_pelicula === id_pelicula);
            const modalRating = document.getElementById('modalRating');
            modalRating.textContent = `Calificación: ${pelicula.calificacion_usuarios}/5`;
        })
        .catch(error => {
            console.error('Error al actualizar el promedio de calificación:', error);
        });
}

function closeModal() {
    const modal = document.getElementById('movieModal');
    const modalTitle = document.getElementById('modalTitle');
    const modalImage = document.getElementById('modalImage');
    const modalDescription = document.getElementById('modalDescription');
    const modalCategories = document.getElementById('modalCategories');
    const modalYear = document.getElementById('modalYear');
    const modalRating = document.getElementById('modalRating');
    const tiempoContainer = document.getElementById('tiempoVisualizacion');
    const modalWatchButton = document.getElementById('modalWatchButton');

    modalWatchButton.disabled = false;
    modalTitle.textContent = '';
    modalImage.src = '';
    modalDescription.textContent = '';
    modalCategories.innerHTML = '';
    modalYear.textContent = '';
    modalRating.textContent = '';

    if (tiempoContainer) {
        tiempoContainer.remove();
    }

    modal.style.display = 'none';
}

document.querySelector('.close').addEventListener('click', closeModal);

window.addEventListener('click', (event) => {
    const modal = document.getElementById('movieModal');
    if (event.target === modal) {
        closeModal();
    }
});