
<?php //NO TOCAR
session_start();
if (!isset($_SESSION['pk_usuario'])) {
    // Si no hay sesión iniciada, redirige a la página de inicio de sesión
    header("Location: login.php");
    exit();
}

$id_usuario = $_SESSION['pk_usuario']; // Obtener el ID de usuario desde la sesión NO TOCARRRRRRRRRR
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

        .navbar .minijuego {
            color: white;
            text-decoration: none;
            padding: 10px;
            background-color: #880ECE;
            border-radius: 5px;
            font-size: 16px;
        }

        .navbar .minijuego:hover {
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

    </style>
</head>
<body>

    <!-- Barra de navegación -->
    <div class="navbar">
    <div class="icono">
        <img src="logo.png">
    </div>
    <a href="editar_usuario.php?pk_usuario=<?= $id_usuario ?>">Usuario</a> <!-- $id_usuario es una variable en PHP con el ID del usuario -->
    <div class="busqueda">
        <input type="text" placeholder="Buscar...">
    </div>
    <a href="#" class="minijuego">Minijuego</a>
</div>

    <!-- Barra lateral -->
    <div class="sidebar">
        <h2>Canciones</h2>
        <a href="#">Starboy</a>
        <a href="#">save you tears</a>
        <a href="#">out of time</a>
        <a href="#">less that zero</a>
    </div>

    <!-- Contenido principal con botones -->
    <div class="content">
        <h1>Novedades de hoy</h1>
        <button>canciones</button>
        <button>canciones</button>
        <button>canciones</button>
        <button>canciones</button>
        <button>canciones</button>
        <button>canciones</button>
    </div>
</body>
</html>