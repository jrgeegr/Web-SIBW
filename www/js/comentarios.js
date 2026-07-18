/* js/comentarios.js */

// Referencias a elementos del DOM
const panel = document.getElementById('panel-comentarios');
const trigger = document.getElementById('trigger-comentarios');
const cerrarPanel = document.getElementById('btn-cerrar-panel');
const btnNuevo = document.getElementById('btn-nuevo-comentario');
const contenedorForm = document.getElementById('contenedor-formulario');
const cerrarFormulario = document.getElementById('btn-cerrar-form');
const formComentario = document.getElementById('form-comentario');
const campoTexto = document.getElementById('texto-comentario');

// Gestión Visual del Panel (Abrir/Cerrar)
trigger.onmouseenter = function() {
    panel.classList.add('abierto');
};

function fcerrarPanel() {
    panel.classList.remove('abierto');
    formComentario.reset();
    contenedorForm.classList.add('oculto');
    btnNuevo.style.display = 'block';
}

cerrarPanel.onclick = function() {
    fcerrarPanel();
};

window.onclick = function(event) {
    if (panel.classList.contains('abierto') && !panel.contains(event.target)) {
        fcerrarPanel();
    }
};

// Gestión del Formulario
btnNuevo.onclick = function() {
    contenedorForm.classList.remove('oculto');
    this.style.display = 'none';
};

cerrarFormulario.onclick = function() {
    contenedorForm.classList.add('oculto');
    btnNuevo.style.display = 'block';
    formComentario.reset();
};


// Validaciones antes de enviar a la Base de Datos
formComentario.onsubmit = function(e) {
    // IMPORTANTE: Aquí NO ponemos e.preventDefault() 
    // permitimos que el formulario viaje a noticia.php para ser guardado

    const nombre = document.getElementById('nombre').value.trim();
    const emailValue = document.getElementById('email').value.trim();
    const texto = campoTexto.value.trim();

    if (nombre === "" || emailValue === "" || texto === "") {
        alert("Todos los campos son obligatorios.");
        e.preventDefault(); // Detenemos el envío solo si hay error
        return false; 
    }

    const regexEmail = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    if (!regexEmail.test(emailValue)) {
        alert("El formato del email no es válido.");
        e.preventDefault();
        return false;
    }

    // Si todo está bien, el navegador enviará los datos vía POST a PHP
    return true;
};

// Escuchador para poner localidades en MAYÚSCULAS automáticamente
campoTexto.addEventListener('input', function() {
    let contenido = this.value;

    localidadesCercanas.forEach(pueblo => {
        const regex = new RegExp(`\\b${pueblo}\\b`, 'gi');
        contenido = contenido.replace(regex, pueblo.toUpperCase());
    });

    if (this.value !== contenido) {
        this.value = contenido;
    }
});