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
    <!-- Carátula del álbum -->
    <div class="cover" style="background-image: url('uploads/album_placeholder.jpg');"></div>

    <!-- Información de la canción -->
    <div class="song-info">
      <div class="song-title">
        <?php echo htmlspecialchars(pathinfo($rutaCancion, PATHINFO_FILENAME)); ?>
      </div>
      <div class="song-artist">Artista Desconocido</div>
    </div>

    <!-- Barra de progreso -->
    <div class="progress-bar">
      <div class="progress" id="progress"></div>
    </div>

    <!-- Controles -->
    <div class="controls">
      <button onclick="restartSong()">⏮️</button>
      <button onclick="togglePlayPause()">▶️</button>
      <button onclick="forwardSong()">⏭️</button>
    </div>

    <!-- Botón de regreso -->
    <a href="index.php" class="btn-back">Regresar al Inicio</a>
    <audio id="audio" src="uploads/<?php echo htmlspecialchars($rutaCancion); ?>"></audio>
  </div>

  <script>
    const audio = document.getElementById("audio");
    const progress = document.getElementById("progress");
    let isPlaying = false;

    // Control de reproducción y pausa
    function togglePlayPause() {
      if (isPlaying) {
        audio.pause();
        isPlaying = false;
        document.querySelector('.controls button:nth-child(2)').innerHTML = '▶️';
      } else {
        audio.play();
        isPlaying = true;
        document.querySelector('.controls button:nth-child(2)').innerHTML = '⏸️';
      }
    }

    // Reiniciar la canción
    function restartSong() {
      audio.currentTime = 0;
      if (!isPlaying) {
        togglePlayPause();
      }
    }

    // Adelantar canción (placeholder para funcionalidad futura)
    function forwardSong() {
      audio.currentTime = Math.min(audio.duration, audio.currentTime + 10);
    }

    // Actualizar barra de progreso
    audio.ontimeupdate = () => {
      const percentage = (audio.currentTime / audio.duration) * 100;
      progress.style.width = percentage + "%";
    };
  </script>
</body>
</html>

