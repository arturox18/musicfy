<?php
// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "musica");

// Verificar conexión
if ($conexion->connect_error) {
    die("Error en la conexión: " . $conexion->connect_error);
}

// Obtener el término de búsqueda
$q = $_GET['q'] ?? '';
$q = '%' . $conexion->real_escape_string($q) . '%'; // Agregar comodines para búsqueda

// Preparar la consulta SQL
$stmt = $conexion->prepare("
    SELECT c.pk_cancion, c.nombre AS cancion_nombre, c.cancion AS cancion_url, u.nom_usuario AS artista_nombre, 
           al.titulo AS album_nombre, u.pk_usuario AS pk_usuario
    FROM cancion c
    LEFT JOIN album al ON c.fk_album = al.pk_album
    LEFT JOIN artista ar ON al.fk_artista = ar.pk_artista
    LEFT JOIN usuario u ON ar.fk_usuario = u.pk_usuario
    WHERE c.nombre LIKE ? OR u.nom_usuario LIKE ? OR al.titulo LIKE ?
    ORDER BY c.nombre ASC;
");

// Vincular parámetros
$stmt->bind_param("sss", $q, $q, $q);

// Ejecutar consulta
$stmt->execute();

// Obtener resultados
$resultado = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados de Búsqueda</title>
    <link rel="stylesheet" href="css/diseno_index.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
</head>

<body>

    <div class="navbar">
        <div class="navbar-left">
            <a href="index.html">
                <img src="./imagenes/logo.png" alt="Logo" class="logo">
            </a>
            <h1>Resultados de la búsqueda</h1>
        </div>
        <div class="navbar-right">
            <a href="index_pro.php" class="nav-link cerrar-sesion">Volver</a>
        </div>
    </div>

    <div class="content">
        <?php if ($resultado->num_rows > 0): ?>
            <?php while ($fila = $resultado->fetch_assoc()): ?>
                <?php
                $pk_cancion = $fila['pk_cancion'];
                $nombre_cancion = $fila['cancion_nombre'];
                $artista_nombre = $fila['artista_nombre'];
                $album_nombre = $fila['album_nombre'];
                $cancion_url = urlencode($fila['cancion_url']);  // Asegúrate de que esta columna exista
                $pk_usuario = $fila['pk_usuario'];  // Obtener pk_usuario de la consulta

                // Consulta para obtener detalles del artista y álbum
                $sql_artista = "
                SELECT u.nom_usuario, p.nombre AS nombre_artista, al.caratula
                FROM usuario u
                JOIN artista a ON u.pk_usuario = a.fk_usuario
                JOIN album al ON al.fk_artista = a.pk_artista
                JOIN persona p ON u.fk_persona = p.pk_persona
                WHERE al.titulo = ?";

                $stmt_artista = $conexion->prepare($sql_artista);
                $stmt_artista->bind_param("s", $album_nombre);
                $stmt_artista->execute();
                $resultado_artista = $stmt_artista->get_result();
                $artista = $resultado_artista->fetch_assoc();

                $nombre_artista = $artista['nombre_artista'] ?? 'Desconocido';
                $caratula_album = $artista['caratula'] ?? 'imagenes/logo.png';
                ?>

                <div class="song-item">
                    <div class="song-card">
                        <img src="imagenes/<?= htmlspecialchars($caratula_album) ?>" alt="<?= htmlspecialchars($nombre_cancion) ?>" class="album-cover">
                        <div class="song-info">
                            <h3 class="song-title"><?= htmlspecialchars($nombre_cancion) ?></h3>
                            <p class="artist-name"><?= htmlspecialchars($nombre_artista) ?></p>
                            <p class="album-name"><?= htmlspecialchars($album_nombre) ?></p>
                        </div>
                    </div>

                    <button class="play-button" onclick="window.location.href='reproductor.php?cancion=<?= urlencode($fila['cancion_url']) ?>&pk_usuario=<?= urlencode($pk_usuario) ?>'">
                        <i class="fa fa-play"></i> Reproducir
                    </button>
                </div>

            <?php endwhile; ?>
        <?php else: ?>
            <p>No se encontraron canciones para la búsqueda</p>
        <?php endif; ?>
    </div>

</body>

</html>

<?php
// Cerrar conexión
$stmt->close();
$conexion->close();
?>
