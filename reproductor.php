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
    /* Estilo del cuerpo */
    body {
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      margin: 0;
      background: linear-gradient(135deg, #121212, #282828);
      color: #fff;
      font-family: 'Arial', sans-serif;
    }

    /* Contenedor del reproductor */
    .player {
      width: 400px;
      background: #181818;
      border-radius: 20px;
      box-shadow: 0 8px 15px rgba(0, 0, 0, 0.5);
      text-align: center;
      padding: 20px;
      overflow: hidden;
    }

    /* Carátula del álbum */
    .cover {
      width: 100%;
      height: 300px;
      background: url('uploads/cover_placeholder.jpg') center center / cover no-repeat;
      border-radius: 15px;
      margin-bottom: 20px;
    }

    /* Información de la canción */
    .song-info {
      margin-bottom: 15px;
    }

    .song-title {
      font-size: 1.2em;
      font-weight: bold;
      margin: 5px 0;
    }

    .song-artist {
      font-size: 0.9em;
      color: #b3b3b3;
    }

    /* Barra de progreso */
    .progress-bar {
      width: 100%;
      height: 8px;
      background: #404040;
      border-radius: 5px;
      overflow: hidden;
      position: relative;
      margin: 15px 0;
    }

    .progress {
      width: 0%;
      height: 100%;
      background: #1db954;
      border-radius: 5px;
      transition: width 0.2s;
    }

    /* Controles */
    .controls {
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 15px;
      margin-top: 15px;
    }

    .controls button {
      background: none;
      border: none;
      color: #fff;
      font-size: 1.8em;
      cursor: pointer;
      transition: transform 0.3s, color 0.3s;
    }

    .controls button:hover {
      color: #1db954;
      transform: scale(1.2);
    }

    /* Botón de regreso */
    .btn-back {
      display: inline-block;
      margin-top: 20px;
      padding: 10px 20px;
      font-size: 0.9em;
      color: #fff;
      background: #1db954;
      border: none;
      border-radius: 50px;
      cursor: pointer;
      text-decoration: none;
      transition: background-color 0.3s, transform 0.3s;
    }

    .btn-back:hover {
      background: #1ed760;
      transform: scale(1.05);
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

    <a href="index_pro.php" class="btn-back">Regresar al Inicio</a>
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
