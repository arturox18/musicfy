<?php
session_start();

// Conectar a la base de datos
$conexion = new mysqli("localhost", "root", "", "musica");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $nombre = $_POST['nombre'];
    $genero = $_POST['genero'];
    $duracion = $_POST['duracion'];
    $album = $_POST['album']; 

   
    $archivo = $_FILES['audio']['tmp_name'];
    $nombreArchivo = $_FILES['audio']['name'];
    $rutaDestino = "uploads/" . basename($nombreArchivo);

   
    $tiposPermitidos = ['audio/mpeg', 'audio/wav', 'audio/ogg'];
    $tamanoMaximo = 5 * 1024 * 1024 * 1024; // 5 GB

    
    if (in_array($_FILES['audio']['type'], $tiposPermitidos) && $_FILES['audio']['size'] <= $tamanoMaximo) {
        
        if (move_uploaded_file($archivo, $rutaDestino)) {
           
            $sql = "INSERT INTO cancion (nombre, genero, duracion, cancion, fk_album) 
                    VALUES (?, ?, ?, ?, ?)";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("ssssi", $nombre, $genero, $duracion, $nombreArchivo, $album);

            if ($stmt->execute()) {
                
                header("Location: index_pro.php");
                exit(); 
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
