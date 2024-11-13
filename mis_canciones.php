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

// Consulta con INNER JOIN para obtener las canciones relacionadas con el usuario logueado
$sql = "
    SELECT cancion.nombre AS nombre_cancion, cancion.cancion, album.titulo AS album_titulo, album.caratula AS album_car
    FROM cancion
    INNER JOIN album ON cancion.fk_album = album.pk_album
    INNER JOIN artista ON album.fk_artista = artista.pk_artista
    INNER JOIN usuario ON artista.fk_usuario = usuario.pk_usuario
    WHERE usuario.pk_usuario = ?
    ORDER BY cancion.pk_cancion DESC
";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $pk_usuario);
$stmt->execute();
$resultado = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Canciones</title>
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

        .navbar {
            width: 100%;
            background-color: #333;
            color: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 20px;
            position: fixed;
            top: 0;
            z-index: 1;
        }

        .sidebar {
            width: 250px;
            background-color: #333333;
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

        .content {
            margin-left: 250px;
            padding: 70px 20px 20px;
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
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

        .cancion-card {
            background-color: #fff;
            color: #333;
            padding: 15px;
            margin: 10px;
            border-radius: 8px;
            width: 300px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .cancion-card img {
            max-width: 100%;
            height: auto;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

    <div class="navbar">
        <a href="panel_usuario.php?pk_usuario=<?= $pk_usuario ?>">Usuario</a>
        <a href="inicio_sesion.html" class="cerrar-sesion">Cerrar Sesión</a>
    </div>

    <div class="sidebar">
        <h2>Canciones</h2>
        <a href="mis_canciones.php">Mis Canciones</a>
        <a href="mis_albums.php">Mis Álbumes</a>
    </div>

    <div class="content">
        <h1>Mis Canciones</h1>
        
        <?php
        // Verificar si hay canciones para mostrar
        if ($resultado->num_rows > 0) {
            // Generar un bloque para cada canción
            while ($fila = $resultado->fetch_assoc()) {
                echo "<div class='cancion-card'>";
                echo "<h3>" . htmlspecialchars($fila['nombre_cancion']) . "</h3>";
                echo "<p><strong>Álbum:</strong> " . htmlspecialchars($fila['album_titulo']) . "</p>";
                
                // Verificar si hay una carátula y mostrarla
                if (!empty($fila['album_car'])) {
                    echo "<img src='path/to/caratulas/" . htmlspecialchars($fila['album_car']) . "' alt='Carátula del álbum'>";
                }
                
                echo "<button onclick=\"window.location.href='reproductor.php?cancion=" . urlencode($fila['cancion']) . "&pk_usuario=" . $pk_usuario . "'\">" .  "</button>";

                echo "</div>";
            }
        } else {
            echo "<p>No tienes canciones disponibles.</p>";
        }

        // Liberar el resultado y cerrar conexión
        $resultado->free();
        $conexion->close();
        ?>

    </div>

</body>
</html>
