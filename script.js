// Seleccionar los elementos necesarios
const toggleButton = document.getElementById('toggle-sidebar');
const sidebar = document.querySelector('.sidebar');

// Alternar la clase "hidden" al hacer clic en el botón
toggleButton.addEventListener('click', () => {
    sidebar.classList.toggle('hidden');
});
