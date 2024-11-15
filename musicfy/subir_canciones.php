<?php
session_start();

// Conectar a la base de datos
$conexion = new mysqli("localhost", "root", "", "musica");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Verifica si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtén los valores del formulario
    $nombre = $_POST['nombre'];
    $genero = $_POST['genero'];
    $duracion = $_POST['duracion'];
    $album = $_POST['album']; // Aquí estamos obteniendo el ID del álbum seleccionado

    // Configuración para el archivo de audio
    $archivo = $_FILES['audio']['tmp_name'];
    $nombreArchivo = $_FILES['audio']['name'];
    $rutaDestino = "uploads/" . basename($nombreArchivo);

    // Array de tipos de archivos permitidos
    $tiposPermitidos = ['audio/mpeg', 'audio/wav', 'audio/ogg'];
    $tamanoMaximo = 5 * 1024 * 1024 * 1024; // 5 GB

    // Verifica el tipo y tamaño de archivo
    if (in_array($_FILES['audio']['type'], $tiposPermitidos) && $_FILES['audio']['size'] <= $tamanoMaximo) {
        // Verifica si el archivo fue subido exitosamente
        if (move_uploaded_file($archivo, $rutaDestino)) {
            // Inserta los datos en la base de datos, relacionando la canción con el álbum
            $sql = "INSERT INTO cancion (nombre, genero, duracion, cancion, fk_album) 
                    VALUES (?, ?, ?, ?, ?)";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("ssssi", $nombre, $genero, $duracion, $nombreArchivo, $album);

            if ($stmt->execute()) {
                echo "Canción subida exitosamente.";
            } else {
                echo "Error al guardar en la base de datos: " . $conexion->error;
            }

            $stmt->close();
        } else {
            echo "Error al subir el archivo. Verifica permisos en la carpeta 'uploads'.";
        }
    } else {
        echo "Tipo de archivo no permitido o tamaño excedido. Debe ser MP3, WAV o OGG.";
    }
}

$conexion->close();
?>
