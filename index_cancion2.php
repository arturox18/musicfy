<?php
// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "musica");

// Verifica la conexión
if ($conexion->connect_error) {
  die("Error de conexión: " . $conexion->connect_error);
}

// Consulta para obtener las canciones y su nombre
$sql = "SELECT pk_cancion, nombre, cancion FROM cancion ORDER BY pk_cancion";
$resultado = $conexion->query($sql);


$stmt = $conexion->prepare("SELECT nom_usuario FROM usuario WHERE pk_usuario = ?");
$stmt->bind_param("i", $pk_usuario);
$stmt->execute();
$resultado_usuario = $stmt->get_result();

if ($resultado_usuario->num_rows > 0) {
  $usuario = $resultado_usuario->fetch_assoc();
  $nombre_usuario = $usuario['nom_usuario'];
}
$stmt->close();

$sql_artista = "SELECT * FROM artista WHERE fk_usuario = '$pk_usuario'";
$resultado_artista = $conexion->query($sql_artista);

?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Listado de Canciones</title>
  <link rel="stylesheet" href="css/diseno_index.css">

</head>

<body>

  <div class="navbar">
    <button id="toggle-sidebar" class="toggle-button">
      <i class="fa fa-bars"></i>
    </button>
    <div class="navbar-left">
      <a href="index.html">
        <img src="./imagenes/logo.png" alt="Logo" class="logo">
      </a>
      <form action="resultados_busqueda.php" method="GET" class="search-form">
        <input type="text" name="q" placeholder="Buscar canciones, artistas, álbumes..." required class="search-input">
        <button type="submit" class="search-button">Buscar</button>
      </form>
      <div id="resultados"></div>


    </div>
    <div class="navbar-right">

    </div>
  </div>


  <div class="sidebar">
    <h2>Opciones</h2>
    <nav>
      <a href="index_pro.php" class="sidebar-link">Inicio</a>

    </nav>
  </div>



  <div class="content">
    <h1>Todas las canciones</h1>
    <?php if ($resultado->num_rows > 0): ?>
      <?php while ($fila = $resultado->fetch_assoc()): ?>
        <?php
        $pk_cancion = $fila['pk_cancion'];
        $nombre_cancion = $fila['nombre'];
        $cancion_url = urlencode($fila['cancion']);

        // Consulta para obtener detalles del artista
        $sql_artista = "
                    SELECT u.nom_usuario, u.pk_usuario, p.nombre AS nombre_artista, al.caratula
                    FROM usuario u
                    JOIN artista a ON u.pk_usuario = a.fk_usuario
                    JOIN album al ON al.fk_artista = a.pk_artista
                    JOIN cancion c ON c.fk_album = al.pk_album
                    JOIN persona p ON u.fk_persona = p.pk_persona
                    WHERE c.pk_cancion = ?";

        $stmt_artista = $conexion->prepare($sql_artista);
        $stmt_artista->bind_param("i", $pk_cancion);
        $stmt_artista->execute();
        $resultado_artista = $stmt_artista->get_result();
        $artista = $resultado_artista->fetch_assoc();

        $nombre_artista = $artista['nombre_artista'] ?? 'Desconocido';
        $pk_artista = $artista['pk_usuario'] ?? null;
        $caratula_album = $artista['caratula'] ?? 'imagenes/logo.png';
        ?>

        <div class="song-item">
          <div class="song-card">
            <img src="imagenes/<?= htmlspecialchars($caratula_album) ?>" alt="<?= htmlspecialchars($nombre_cancion) ?>" class="album-cover">
            <div class="song-info">
              <h3 class="song-title"><?= htmlspecialchars($nombre_cancion) ?></h3>
              <p class="artist-name">
              <p class="artist-name"><?= htmlspecialchars($nombre_artista) ?></p>
              </p>
            </div>
          </div>
          <button class="play-button" onclick="window.location.href='reproductor.php?cancion=<?= $cancion_url ?>&pk_usuario=<?= $pk_usuario ?>'">
            <i class="fa fa-play"></i> Reproducir
          </button>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p>No hay canciones recientes.</p>
    <?php endif; ?>
    <?php $conexion->close(); ?>
  </div>


  <script src="script.js"></script>
</body>

</html>