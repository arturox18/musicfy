<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['pk_usuario'])) {
    header("Location: login.php"); // Redirigir al usuario si no está autenticado
    exit();
}

$conexion = new mysqli("localhost", "root", "", "musica");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Obtener el ID del usuario desde la sesión
$usuario_id = $_SESSION['pk_usuario'];

// Verificar si el usuario existe en la tabla 'usuario'
$sql_usuario = $conexion->prepare("SELECT pk_usuario FROM usuario WHERE pk_usuario = ?");
$sql_usuario->bind_param("i", $usuario_id);
$sql_usuario->execute();
$sql_usuario->store_result();

if ($sql_usuario->num_rows == 0) {
    echo "Error: El usuario no existe en la tabla 'usuario'.";
    exit();
}

$sql_usuario->close();

// Verificar si el usuario es un artista
$sql_artista = $conexion->prepare("SELECT pk_artista FROM artista WHERE fk_usuario = ?");
$sql_artista->bind_param("i", $usuario_id);
$sql_artista->execute();
$sql_artista->store_result();

// Si el usuario no es un artista, insertarlo como artista
if ($sql_artista->num_rows == 0) {
    // El usuario no es un artista, lo insertamos en la tabla 'artista'
    $sql_insert_artista = $conexion->prepare("INSERT INTO artista (fk_usuario) VALUES (?)");
    $sql_insert_artista->bind_param("i", $usuario_id);
    
    if ($sql_insert_artista->execute()) {
        echo "El usuario ha sido registrado como artista.";
    } else {
        echo "Error al registrar el artista: " . $conexion->error;
    }
    $sql_insert_artista->close();
}

// Obtener el pk_artista del usuario (ya sea que se haya registrado como artista o ya existía)
$sql_artista = $conexion->prepare("SELECT pk_artista FROM artista WHERE fk_usuario = ?");
$sql_artista->bind_param("i", $usuario_id);
$sql_artista->execute();
$sql_artista->bind_result($pk_artista);
$sql_artista->fetch();
$sql_artista->close();

// A partir de aquí, puedes proceder a crear el álbum si el usuario es un artista
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener y sanitizar los valores del formulario
    $titulo = $conexion->real_escape_string($_POST['titulo']);
    $fecha = $_POST['fecha'];
    $caratula = $_FILES['caratula']['tmp_name'];
    $nombreCaratula = basename($_FILES['caratula']['name']);
    $rutaDestino = "imagenes/" . $nombreCaratula;

    if (move_uploaded_file($caratula, $rutaDestino)) {
        // Insertar el álbum en la base de datos
        $sql_album = $conexion->prepare("INSERT INTO album (titulo, fecha, caratula, fk_artista) VALUES (?, ?, ?, ?)");
        $sql_album->bind_param("sssi", $titulo, $fecha, $nombreCaratula, $pk_artista);

        if ($sql_album->execute()) {
            echo "Álbum creado exitosamente.";
        } else {
            echo "Error al guardar el álbum: " . $conexion->error;
        }
        $sql_album->close();
    } else {
        echo "Error al subir la carátula.";
    }
}

$conexion->close();
?>
