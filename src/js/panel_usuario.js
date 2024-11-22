document.addEventListener('DOMContentLoaded', () => {
    loadUserData();
    loadUserSubscriptions();


    document.getElementById('logoutBtn').addEventListener('click', logout);
    document.getElementById('guardarCambiosBtn').addEventListener('click', updateUserData);
    document.getElementById('elegirPlanBtn').addEventListener('click', chooseSubscriptionPlan);
});


function showNotification(message, isError = false) {
    const notification = document.getElementById('notification');
    notification.textContent = message;
    notification.className = `notification ${isError ? 'error' : ''} show`;

    setTimeout(() => {
        notification.classList.remove('show');
    }, 3000);
}


function loadUserData() {
    fetch(`/swagplay/src/api/safeUsuarios.php`)
        .then(res => res.json())
        .then(data => {
            if (data) {
                document.getElementById('telefono').value = data.telefono || '';
                document.getElementById('ciudad').value = data.ciudad || '';
                document.getElementById('pais').value = data.pais || '';
                document.getElementById('tarjetaNumero').value = data.tarjetaNumero || '';
                document.getElementById('cvv').value = data.cvv || '';
                document.getElementById('nombreTarjeta').value = data.nombreTarjeta || '';
            } else {
                showNotification('Error al cargar los datos del usuario.', true);
                console.log(data)
            }
        })
        .catch(err => showNotification('Error al cargar los datos: ' + err.message, true));
}


function updateUserData() {
    const perfilForm = document.getElementById('perfilForm');
    const formData = new FormData(perfilForm);
    fetch('/swagplay/src/api/safeUsuarios.php', { method: 'PUT', body: formData })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showNotification('Datos actualizados correctamente.');
            } else {
                showNotification('Error al actualizar los datos.', true);
            }
        })
        .catch(err => showNotification('Error al actualizar los datos: ' + err.message, true));
}


function chooseSubscriptionPlan() {
    let plan = document.getElementById('planSuscripcion').value;
    plan = plan == 3 ? 4 : plan;
    fetch(`/swagplay/src/api/safeSuscripciones.php`, {
        method: 'POST',
        body: JSON.stringify({ plan })
    })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showNotification('Plan de suscripción actualizado.');
                loadUserSubscriptions();
            } else {
                showNotification('Error al actualizar el plan.', true);
            }
        })
        .catch(err => showNotification('Error al actualizar el plan: ' + err.message, true));
}


function loadUserSubscriptions() {
    fetch(`/swagplay/src/api/safeSuscripciones.php`)
        .then(res => res.json())
        .then(data => {

            if (data.suscripciones && Array.isArray(data.suscripciones)) {
                const suscripcionesTabla = document.getElementById('suscripcionesTabla');
                suscripcionesTabla.innerHTML = '';


                data.suscripciones.forEach(subscription => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${subscription.plan}</td>
                        <td><button class="btn-delete" data-id="${subscription.pantallas_simultaneas}">Eliminar</button></td>
                    `;
                    suscripcionesTabla.appendChild(row);


                    row.querySelector('.btn-delete').addEventListener('click', () => deleteSubscription(subscription.pantallas_simultaneas));
                });
            } else {
                showNotification('Suscribete para empezar a explorar peliculas');
                suscripcionesTabla.innerHTML = '';
            }
        })
        .catch(err => showNotification('Error al cargar las suscripciones: ' + err.message, true));
}



function deleteSubscription(pantallas_simultaneas) {
    fetch(`/swagplay/src/api/safeSuscripciones.php`, {
        method: 'DELETE',
        body: JSON.stringify({ pantallas_simultaneas })
    })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showNotification('Suscripción eliminada.');
                loadUserSubscriptions();
            } else {
                showNotification('Error al eliminar la suscripción.', true);
            }
        })
        .catch(err => showNotification('Error al eliminar la suscripción: ' + err.message, true));
}


function logout() {
    fetch(`/swagplay/src/api/logout.php`, { method: 'POST' })
        .then(res => {
            if (res.ok) {
                window.location.href = '../../index.php';
            } else {
                showNotification('Error al cerrar sesión.', true);
            }
        })
        .catch(err => showNotification('Error al cerrar sesión: ' + err.message, true));
}