const apiUrl = "http://localhost/BarberApp_API/controllers/";

// Referencias a elementos del DOM
const modal = document.getElementById('modalForm');
const btnNuevo = document.getElementById('btn-nuevo');
const closeButtons = document.querySelectorAll('.modal-close');
const cancelarButtons = document.querySelectorAll('.btn-cancelar');
const form = document.getElementById('form');
const titleForm = document.getElementById('title-form');

//Verificar sesion 
verificarSesion();

function verificarSesion(){
    const isLoggedIn = localStorage.getItem('isLoggedIn');
    if (!isLoggedIn) {
        window.location.href = "/"; // Redirigir al login
        return false;
    }
    return true;
}

// Abrir modal
btnNuevo.addEventListener('click', () => {
    modal.classList.add('active');
});

function abrirModal(){
    modal.classList.add('active');
}

// Cerrar modal
cancelarButtons.forEach(button => {
    button.addEventListener('click', () => {
        cerrarModal();
    });
});

// Cerrar modal al hacer clic fuera
modal.addEventListener('click', (e) => {
    if (e.target === modal) {
        cerrarModal();
    }
});

function cerrarVentanaModal(){
    titleForm.innerHTML = "Nuevo";
    modal.classList.remove('active');
    form.reset();
}