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
    <link rel="stylesheet" href="css/diseno_index.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #1b1b1b;
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

        .navbar .minijuego,
        .navbar .cerrar-sesion {
            color: white;
            text-decoration: none;
            padding: 10px;
            background-color: #880ECE;
            border-radius: 5px;
            font-size: 16px;
        }

        .navbar .minijuego:hover,
        .navbar .cerrar-sesion:hover {
            background-color: #CE3AFF;
        }

        /* Barra lateral */
        .sidebar {
            width: 250px;
            background-color: #a810ad;
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
        }

        .content h1 {
            margin-bottom: 20px;
            text-align: center;
        }

        /* Grid de álbumes */
        .album-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            justify-items: center;
        }

        .album {
            background-color: #333333;
            border-radius: 10px;
            padding: 15px;
            width: 300px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .album:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.2);
        }

        .album img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 10px;
            border: 2px solid #ddd;
        }

        .album-info {
            margin-top: 15px;
            font-size: 18px;
            color: #F2F3FF;
            font-weight: 600;
        }

        .album-info span {
            display: block;
            font-size: 14px;
            color: #777;
            font-weight: 400;
            margin-top: 5px;
        }

        /* Estilos para el botón de regresar */
        .btn-regresar {
            display: inline-block;
            padding: 10px 20px;
            background-color: #880ECE;
            color: white;
            font-size: 16px;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 20px;
            text-align: center;
        }

        .btn-regresar:hover {
            background-color: #CE3AFF;
        }
    </style>
</head>

<body>

 <button id="toggle-sidebar" class="toggle-button">
            <i class="fa fa-bars"></i>
        </button>
    <!-- Barra de navegación -->
    <div class="navbar">

        <button id="toggle-sidebar" class="toggle-button">
            <i class="fa fa-bars"></i>
        </button>

        <div class="navbar-left">
            <a href="index.html">
                <img src="./imagenes/logo.png" alt="Logo" class="logo">
            </a>

        </div>
        <a href="#" class="minijuego">Minijuego</a>
        <a href="index_pro.php" class="cerrar-sesion">Volver</a>
    </div>

    <div class="navbar">
        <div class="navbar-left">
            <a href="index.html">
                <img src="./imagenes/logo.png" alt="Logo" class="logo">
            </a>
            <h1>Mis albumes</h1>
        </div>
        <div class="navbar-right">
            <a href="index_pro.php" class="nav-link cerrar-sesion">Volver</a>
        </div>
    </div>

    <!-- Barra lateral -->
    <div class="sidebar">
        <h2>Opciones</h2>
        <a href="formulario_album.html?pk_usuario=<?= $pk_usuario ?>" class="sidebar-link">Crear Álbum</a>
    </div>

    <!-- Contenido principal con los álbumes del usuario -->
    <div class="content">

        <div class="album-grid">
            <?php
            // Verificar si el usuario tiene álbumes
            if ($resultado->num_rows > 0) {
                // Mostrar los álbumes
                while ($album = $resultado->fetch_assoc()) {
                    echo '<div class="album">';
                    echo '<img src="imagenes/' . htmlspecialchars($album['caratula']) . '" alt="Carátula del álbum">';
                    echo '<div class="album-info">';
                    echo '<p>' . htmlspecialchars($album['titulo']) . '</p>';
                    echo '<span>' . htmlspecialchars($album['fecha']) . '</span>';
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
        

        
    <script src="script.js"></script>
</body>

</html>