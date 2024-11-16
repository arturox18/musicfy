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


$sql = "SELECT pk_cancion, nombre, cancion FROM cancion ORDER BY pk_cancion DESC LIMIT 5";
$resultado = $conexion->query($sql);

// Verificar si el usuario es un artista (consulta a la tabla de artistas)
$sql_artista = "SELECT * FROM artista WHERE fk_usuario = '$pk_usuario'";
$resultado_artista = $conexion->query($sql_artista);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menú de Inventario</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #880ECE;
            margin: 0;
            padding: 0;
            display: flex;
            height: 100vh;
            flex-direction: column;
        }

        /* Barra lateral */
        .sidebar {
            width: 250px;
            background-color: black;
            color: white;
            height: 100vh;
            position: fixed;
            top: 50px;
            display: flex;
            flex-direction: column;
            padding: 20px;
        }

        .sidebar a {
            color: white;
            padding: 15px 0;
            text-decoration: none;
            font-size: 18px;
        }

        .sidebar a:hover {
            background-color: #CE3AFF;
            padding-left: 10px;
            transition: 0.3s;
        }

        /* Contenido */
        .content {
            margin-left: 250px;
            padding: 70px 20px 20px;
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        .content h1 {
            color: white;
            margin-bottom: 20px;
        }

        .content button {
            background-color: #333333;
            color: white;
            border: none;
            padding: 15px 30px;
            font-size: 18px;
            margin: 10px;
            cursor: pointer;
            border-radius: 5px;
        }

        .content button:hover {
            background-color: #CE3AFF;
        }

        .logo{
            border-radius: 100px;
        }

        .video-background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: -1;
        }

      
        /* Opcional: opacidad para oscurecer un poco el video */
        .overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 0;

        }

         /*navbar*/
         * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
}

.navbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background-color: #333;
    padding: 10px 20px;
    color: #fff;
}

.navbar-left {
    display: flex;
    align-items: center;
    gap: 15px;
}

.navbar-logo .logo {
    height: 35px;
    width: auto;
}

.nav-link {
    color: #fff;
    text-decoration: none;
    font-size: 16px;
    padding: 5px 10px;
    transition: color 0.3s, background-color 0.3s;
}

.nav-link:hover {
    color:black;
}

.search-input {
    width: 200px;
    padding: 5px 10px;
    font-size: 14px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

.navbar .search-input:focus {
    outline: none;
    border-color: #ffd700;
}

.navbar-right {
    display: flex;
    align-items: center;
    gap: 15px;
}




    </style>
</head>
<body>


    <video class="video-background" autoplay muted loop>
        <source src="ruta-del-video.mp4" type="video/mp4">
        Tu navegador no soporta videos en HTML5.
    </video>


    <!-- Barra de navegación -->
    <div class="navbar">
        <div class="navbar-left">
            <div class="navbar-logo">
                <a href="index.html">
                    <img src="./imagenes/logo.png" alt="Logo" class="logo">
                </a>
            </div>
            <a href="panel_usuario.php?pk_usuario=<?= $pk_usuario ?>" class="nav-link">Usuario</a>
            <input type="text" placeholder="Buscar..." aria-label="Buscar" class="search-input">
        </div>
        <div class="navbar-right">
            <a href="#" class="nav-link minijuego">Minijuego</a>
            <a href="inicio_sesion.html" class="nav-link cerrar-sesion">Cerrar Sesión</a>
        </div>
    </div>
    <!-- Barra lateral -->
    <div class="sidebar">
        <h2>Canciones</h2>

        <?php if ($resultado_artista->num_rows > 0): ?>
            <!-- El usuario es artista -->
            <a href="formulario_album.html?pk_usuario=<?= $pk_usuario ?>">Subir Álbum</a>
            <a href="mis_albums.php?pk_usuario=<?= $pk_usuario ?>">Ver mis Álbumes</a>
            <a href=" formulario_subir_canciones.php?pk_usuario=<?= $pk_usuario ?>">subir canciones</a>
            <a href="mis_canciones.php?pk_usuario=<?= $pk_usuario ?>">Ver mis Canciones</a>

        <?php else: ?>
            <!-- El usuario no es artista -->
            <a href="registrar_artista.php?pk_usuario=<?= $pk_usuario ?>">Registrarte como Artista</a>
            

           
        <?php endif; ?>

        <a href="index_cancion.php">Todas las canciones</a>
    </div>

    <!-- Contenido principal con botones dinámicos para las canciones -->
    <div class="content">
        <h1>Novedades recientes</h1>
        <?php
        // Verificar si hay canciones para mostrar
        if ($resultado->num_rows > 0) {
            // Generar un botón para cada canción
            while ($fila = $resultado->fetch_assoc()) {
                $pk_cancion = $fila['pk_cancion'];
                $nombre_cancion = $fila['nombre'];
                echo "<button onclick=\"window.location.href='reproductor.php?cancion=" . urlencode($fila['cancion']) . "&pk_usuario=" . $pk_usuario . "'\">" . htmlspecialchars($nombre_cancion) . "</button>";
            }
        } else {
            echo "<p>No hay canciones recientes.</p>";
        }
        // Liberar el resultado y cerrar conexión
        $resultado->free();
        $conexion->close();
        ?>
    </div>

</body>
</html>
