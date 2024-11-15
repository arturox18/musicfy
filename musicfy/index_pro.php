<?php
// Iniciar sesión y verificar usuario
session_start();
if (!isset($_SESSION['pk_usuario'])) {
    header("Location: login.php");
    exit();
}

$pk_usuario = $_SESSION['pk_usuario'];

// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "musica");

// Verifica la conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Consulta de canciones
$sql = "SELECT pk_cancion, nombre, cancion FROM cancion ORDER BY pk_cancion DESC LIMIT 5";
$resultado = $conexion->query($sql);

// Inicializar la variable por defecto para el nombre de usuario
$nombre_usuario = "Desconocido"; // Valor por defecto

// Consulta del nombre del usuario usando sentencias preparadas
$stmt = $conexion->prepare("SELECT nom_usuario FROM usuario WHERE pk_usuario = ?");
$stmt->bind_param("i", $pk_usuario);
$stmt->execute();
$resultado_usuario = $stmt->get_result();

// Verificar si se encontró el usuario
if ($resultado_usuario->num_rows > 0) {
    $usuario = $resultado_usuario->fetch_assoc();
    $nombre_usuario = $usuario['nom_usuario']; // Asignar el nombre del usuario
}

$stmt->close();

// Verificar si el usuario es un artista
$sql_artista = "SELECT * FROM artista WHERE fk_usuario = '$pk_usuario'";
$resultado_artista = $conexion->query($sql_artista);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú de Inventario</title>
    <link rel="stylesheet" href="diseno_index.css">
</head>
<body>

    <!-- Barra de navegación -->
    <div class="navbar">
        <div class="navbar-left">
            <a href="index.html">
                <img src="./imagenes/logo.png" alt="Logo" class="logo">
            </a>
            <a href="panel_usuario.php?pk_usuario=<?= $pk_usuario ?>" class="nav-link"> <?= htmlspecialchars($nombre_usuario) ?></a>
            <input type="text" placeholder="Buscar..." aria-label="Buscar" class="search-input">
        </div>
        <div class="navbar-right">
            <a href="#" class="nav-link minijuego">Minijuego</a>
            <a href="inicio_sesion.html" class="nav-link cerrar-sesion">Cerrar Sesión</a>
        </div>
    </div>

    <!-- Barra lateral (SIEMPRE visible) -->
    <div class="sidebar">
        <h2>Canciones</h2>
        <nav>
            <?php if ($resultado_artista->num_rows > 0): ?>
                <a href="formulario_album.html?pk_usuario=<?= $pk_usuario ?>" class="sidebar-link">Subir Álbum</a>
                <a href="mis_albums.php?pk_usuario=<?= $pk_usuario ?>" class="sidebar-link">Ver mis Álbumes</a>
                <a href="formulario_subir_canciones.php?pk_usuario=<?= $pk_usuario ?>" class="sidebar-link">Subir Canciones</a>
                <a href="mis_canciones.php?pk_usuario=<?= $pk_usuario ?>" class="sidebar-link">Ver mis Canciones</a>
            <?php else: ?>
                <a href="registrar_artista.php?pk_usuario=<?= $pk_usuario ?>" class="sidebar-link">Registrarte como Artista</a>
            <?php endif; ?>
            <a href="index_cancion.php" class="sidebar-link">Todas las canciones</a>
        </nav>
    </div>

    <!-- Contenido principal -->
    <div class="content">
        <h1>Novedades recientes</h1>
        <?php
        if ($resultado->num_rows > 0) {
            while ($fila = $resultado->fetch_assoc()) {
                $pk_cancion = $fila['pk_cancion'];
                $nombre_cancion = $fila['nombre'];
                $cancion_url = urlencode($fila['cancion']);
                
                // Consulta para obtener el nombre del artista y la carátula del álbum usando sentencias preparadas
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
                
                // Verificar si se encuentra el artista
                if ($resultado_artista->num_rows > 0) {
                    $artista = $resultado_artista->fetch_assoc();
                    $nombre_artista = $artista['nombre_artista'];  // Nombre del artista
                    $pk_artista = $artista['pk_usuario'];  // ID del artista
                    $perfil_artista_url = "perfil_artista.php?pk_usuario=" . $pk_artista;  // Enlace al perfil del artista
                    $caratula_album = $artista['caratula'];  // Ruta de la carátula del álbum
                    
                    // Mostrar el diseño
                    $caratula_imagen = !empty($caratula_album) ? 'imagenes/' . $caratula_album : 'imagenes/logo.png'; // Imagen predeterminada
                    echo "<div class='song-item'>
                            <div class='song-card'>
                                <img src='$caratula_imagen' alt='" . htmlspecialchars($nombre_cancion) . "' class='album-cover'>
                                <div class='song-info'>
                                    <p class='artist-name'><a href='$perfil_artista_url' class='artist-link'>$nombre_artista</a></p>
                                    <p class='song-title'><a href='reproductor.php?cancion=$cancion_url&pk_usuario=$pk_usuario' class='song-link'>$nombre_cancion</a></p>
                                </div>
                            </div>
                          </div>";


                          
                    
                }
                $stmt_artista->close();
            }
        } else {
            echo "<p>No hay canciones recientes.</p>";
        }
        $resultado->free();
        $conexion->close();
        ?>
    </div>

    <script src="script.js"></script>
</body>
</html>
