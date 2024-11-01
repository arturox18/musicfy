<?php
// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "musica");

// Verifica la conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Verificar si se recibió el parámetro 'cancion' en la URL
if (isset($_GET['cancion'])) {
    // Escapar el valor para prevenir inyecciones
    $rutaCancion = $conexion->real_escape_string($_GET['cancion']);
} else {
    die("No se ha seleccionado ninguna canción.");
}

// Cierra la conexión
$conexion->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reproductor de Música</title>
  <style>
    /* Estilos del reproductor */
    body {
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
      background-color: #1a001f;
      color: #f3e5ff;
      font-family: Arial, sans-serif;
    }
    .player {
      width: 320px;
      padding: 20px;
      background: #3d0066;
      border-radius: 15px;
      box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.5);
      text-align: center;
    }
    .progress-bar {
      width: 100%;
      background: #5c2a7a;
      border-radius: 10px;
      overflow: hidden;
      height: 12px;
      margin: 15px 0;
    }
    .progress {
      height: 100%;
      width: 0%;
      background: #e170ff;
      transition: width 0.2s;
    }
    button {
      background: none;
      border: none;
      color: #e170ff;
      font-size: 1.8em;
      cursor: pointer;
      transition: color 0.3s;
    }
    button:hover {
      color: #ffaffd;
    }
  </style>
</head>
<body>
  <div class="player">
    <h2><?php echo htmlspecialchars(pathinfo($rutaCancion, PATHINFO_FILENAME)); ?></h2>
    <div class="progress-bar">
      <div class="progress" id="progress"></div>
    </div>
    <div class="controls">
      <button onclick="playSong()">▶️</button>
      <button onclick="pauseSong()">⏸️</button>
      <button onclick="restartSong()">🔄</button>
    </div>
    <audio id="audio" src="uploads/<?php echo htmlspecialchars($rutaCancion); ?>"></audio>
  </div>

  <script>
    const audio = document.getElementById("audio");
    const progress = document.getElementById("progress");

    // Controles del reproductor
    function playSong() {
      audio.play();
    }

    function pauseSong() {
      audio.pause();
    }

    function restartSong() {
      audio.currentTime = 0;
      audio.play();
    }

    // Actualizar la barra de progreso
    audio.ontimeupdate = () => {
      const percentage = (audio.currentTime / audio.duration) * 100;
      progress.style.width = percentage + "%";
    };
  </script>
</body>
</html>
