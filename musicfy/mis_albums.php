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

// Consulta para obtener los álbumes del usuario logueado
$sql = "
    SELECT album.titulo, album.caratula, album.fecha
    FROM album
    INNER JOIN artista ON album.fk_artista = artista.pk_artista
    INNER JOIN usuario ON artista.fk_usuario = usuario.pk_usuario
    WHERE usuario.pk_usuario = $pk_usuario
";

// Ejecutar la consulta
$resultado = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Álbumes</title>
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

        /* Barra de navegación */
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

        .navbar .icono {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #555;
            margin-right: 20px;
        }

        .navbar .busqueda {
            flex: 1;
            margin: 0 60px;
        }

        .navbar .busqueda input {
            width: 100%;
            padding: 8px;
            border: none;
            border-radius: 5px;
        }

        .navbar .minijuego, .navbar .cerrar-sesion {
            color: white;
            text-decoration: none;
            padding: 10px;
            background-color: #880ECE;
            border-radius: 5px;
            font-size: 16px;
        }

        .navbar .minijuego:hover, .navbar .cerrar-sesion:hover {
            background-color: #CE3AFF;
        }

        /* Barra lateral */
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
            margin-bottom: 20px;
        }

        .album {
            margin-bottom: 20px;
            padding: 10px;
            background-color: white;
            border-radius: 5px;
            width: 300px;
            text-align: center;
        }

        .album img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 5px;
        }

        .album-info {
            margin-top: 10px;
            font-size: 16px;
            color: #333;
        }

    </style>
</head>
<body>

    <!-- Barra de navegación -->
    <div class="navbar">
        <div class="icono">
            <img src="logo.png" alt="Logo">
        </div>
        <a href="panel_usuario.php?pk_usuario=<?= $pk_usuario ?>">Usuario</a>
        <div class="busqueda">
            <input type="text" placeholder="Buscar...">
        </div>
        <a href="#" class="minijuego">Minijuego</a>
        <a href="inicio_sesion.html" class="cerrar-sesion">Cerrar Sesión</a>
    </div>

    <!-- Barra lateral -->
    <div class="sidebar">
        <h2>Mis Álbumes</h2>
        <a href="registrar_album.php">Subir Álbum</a>
        <a href="mis_albums.php">Ver mis Álbumes</a>
    </div>

    <!-- Contenido principal con los álbumes del usuario -->
    <div class="content">
        <h1>Mis Álbumes</h1>
        <?php
        // Verificar si el usuario tiene álbumes
        if ($resultado->num_rows > 0) {
            // Mostrar los álbumes
            while ($album = $resultado->fetch_assoc()) {
                echo '<div class="album">';
                echo '<img src="upload/' . htmlspecialchars($album['caratula']) . '" alt="Carátula">';
                echo '<div class="album-info">';
                echo '<p>Título: ' . htmlspecialchars($album['titulo']) . '</p>';
                echo '<p>Fecha: ' . htmlspecialchars($album['fecha']) . '</p>';
                echo '</div>';
                echo '</div>';
            }
        } else {
            echo "<p>No tienes álbumes.</p>";
        }

        // Liberar el resultado y cerrar la conexión
        $resultado->free();
        $conexion->close();
        ?>
    </div>

</body>
</html>
