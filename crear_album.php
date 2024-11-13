<?php
session_start(); // Iniciar la sesión al comienzo del archivo

$conexion = new mysqli("localhost", "root", "", "musica");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Verificar si el usuario ha iniciado sesión y tiene un ID asignado
if (isset($_SESSION['pk_usuario'])) {
    $usuario_id = $_SESSION['pk_usuario'];
} else {
    die("Error: Usuario no autenticado.");
}

// Verifica si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener y sanitizar los valores del formulario
    $titulo = $conexion->real_escape_string($_POST['titulo']);
    $fecha = $_POST['fecha'];
    $caratula = $_FILES['caratula']['tmp_name'];
    $nombreCaratula = basename($_FILES['caratula']['name']);
    $rutaDestino = "imagenes/" . $nombreCaratula;

    if (move_uploaded_file($caratula, $rutaDestino)) {
        // Preparar la consulta SQL
        $sql = $conexion->prepare("INSERT INTO album (titulo, fecha, caratula, fk_artista) VALUES (?, ?, ?, ?)");
        $sql->bind_param("sssi", $titulo, $fecha, $nombreCaratula, $usuario_id);

        if ($sql->execute()) {
            echo "Álbum creado exitosamente.";
        } else {
            echo "Error al guardar en la base de datos: " . $conexion->error;
        }
        $sql->close();
    } else {
        echo "Error al subir la carátula.";
    }
}

$conexion->close();
?>
