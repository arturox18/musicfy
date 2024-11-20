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
    <title>Administración</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background-color: #880ECE;
            color: white;
        }

        .navbar {
            position: absolute;
            top: 0;
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            background-color: #333;
        }

        .navbar .icono img {
            width: 50px;
            height: 50px;
        }

        .navbar .cerrar-sesion {
            text-decoration: none;
            color: white;
            padding: 10px 20px;
            background-color: #CE3AFF;
            border-radius: 5px;
        }

        .cerrar-sesion:hover {
            background-color: #880ECE;
        }

        .container {
            text-align: center;
        }

        .container h1 {
            margin-bottom: 20px;
        }

        .button-container {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .button-container a {
            text-decoration: none;
            color: white;
            padding: 20px 40px;
            background-color: #333;
            font-size: 18px;
            border-radius: 10px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2);
            transition: transform 0.2s, background-color 0.3s;
        }

        .button-container a:hover {
            background-color: #CE3AFF;
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <!-- Barra de navegación -->
    <div class="navbar">
        <div class="icono">
            <img src="logo.png" alt="Logo">
        </div>
        <a href="inicio_sesion.html" class="cerrar-sesion">Cerrar Sesión</a>
    </div>

    <!-- Contenido principal -->
    <div class="container">
        <h1>Panel de Administración</h1>
        <div class="button-container">
            <a href="admin_cancion.php">Ver todas las Canciones</a>
            <a href="admin_usuario.php">Ver todos los Usuarios</a>
        </div>
    </div>
</body>
</html>
