let cancionActual = null;
let intentos = 0;

async function iniciarJuego() {
    try {
        const response = await fetch('obtenerCancion.php'); // Llama al archivo PHP
        const data = await response.json();

        if (data.error) {
            document.getElementById("feedback").innerText = "Error al cargar la canción.";
        } else {
            cancionActual = data;
            document.getElementById("clue").innerText = `Escucha la canción y adivina el nombre.`;
            document.getElementById("feedback").innerText = '';
            document.getElementById("guess").value = '';
            document.getElementById("audio-player").src = cancionActual.ruta; // Asigna la ruta al reproductor de audio
        }
    } catch (error) {
        document.getElementById("feedback").innerText = "Error al cargar la canción.";
        console.error(error);
    }
}

function checkAnswer() {
    const guess = document.getElementById("guess").value.trim();
    intentos++;

    if (guess.toLowerCase() === cancionActual.nombre.toLowerCase()) {
        document.getElementById("feedback").innerText = `¡Correcto! La canción es "${cancionActual.nombre}". Has adivinado en ${intentos} intentos.`;
        document.getElementById("feedback").classList.remove("error");
        intentos = 0;
    } else {
        document.getElementById("feedback").innerText = "Incorrecto, intenta nuevamente.";
        document.getElementById("feedback").classList.add("error");
    }
}

window.onload = iniciarJuego;
