<?php
// Incluyendo la conexión a la base de datos
include 'conexion.php'; // Asegúrate de que esta línea esté al inicio del archivo

class Cancion {
    // Método para buscar una canción por ID
    public function buscar($pk_cancion) {
        global $conn;
        $sql = "SELECT * FROM cancion WHERE pk_cancion = $pk_cancion";
        return $conn->query($sql);
    }

    // Método para obtener una canción específica por ID
    public function obtenerCancion($pk_cancion) {
        global $conn;
        $sql = "SELECT * FROM cancion WHERE pk_cancion = $pk_cancion";
        return $conn->query($sql)->fetch_assoc();
    }

    // Método para agregar una nueva canción
    public function subirCancion($nombre, $genero, $fk_album, $archivo) {
        global $conn;
        
        // Configuración del archivo de audio
        $nombreArchivo = $archivo['name'];
        $rutaDestino = "uploads/" . basename($nombreArchivo);
        $tipoArchivo = $archivo['type'];
        $tamanoArchivo = $archivo['size'];
        
        // Validación de tipos permitidos y tamaño máximo (5 MB)
        $tiposPermitidos = ['audio/mpeg', 'audio/wav', 'audio/ogg'];
        $tamanoMaximo = 5 * 1024 * 1024; // 5 MB
        
        // Verificar el tipo y tamaño de archivo
        if (in_array($tipoArchivo, $tiposPermitidos) && $tamanoArchivo <= $tamanoMaximo) {
            if (move_uploaded_file($archivo['tmp_name'], $rutaDestino)) {
                $sql = "INSERT INTO cancion (nombre, genero, cancion, fk_album) VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssi", $nombre, $genero, $nombreArchivo, $fk_album);
                
                if ($stmt->execute()) {
                    return "Canción subida exitosamente.";
                } else {
                    return "Error al guardar en la base de datos: " . $conn->error;
                }
                $stmt->close();
            } else {
                return "Error al subir el archivo. Verifica permisos en la carpeta 'uploads'.";
            }
        } else {
            return "Tipo de archivo no permitido o tamaño excedido. Debe ser MP3, WAV o OGG y menor a 5 MB.";
        }
    }

    // Método para actualizar una canción existente
    public function actualizarCancion($pk_cancion, $nombre, $genero, $fk_album, $archivo = null) {
        global $conn;
        
        // Si hay un nuevo archivo de audio, actualizarlo
        $audio_query = "";
        if ($archivo && $archivo['tmp_name']) {
            $nombreArchivo = $archivo['name'];
            $rutaDestino = "uploads/" . basename($nombreArchivo);
            $tipoArchivo = $archivo['type'];
            $tamanoArchivo = $archivo['size'];
            
            $tiposPermitidos = ['audio/mpeg', 'audio/wav', 'audio/ogg'];
            $tamanoMaximo = 5 * 1024 * 1024; // 5 MB
            
            if (in_array($tipoArchivo, $tiposPermitidos) && $tamanoArchivo <= $tamanoMaximo) {
                if (move_uploaded_file($archivo['tmp_name'], $rutaDestino)) {
                    $audio_query = ", cancion = '$nombreArchivo'";
                } else {
                    return "Error al subir el nuevo archivo de audio.";
                }
            } else {
                return "Tipo de archivo no permitido o tamaño excedido.";
            }
        }

        // Actualización de los datos de la canción
        $sql = "UPDATE cancion SET 
                    nombre = '$nombre', 
                    genero = '$genero', 
                    fk_album = '$fk_album' 
                    $audio_query 
                WHERE pk_cancion = '$pk_cancion'";
                
        $result = $conn->query($sql);
        return $result ? "Canción actualizada exitosamente." : "Error al actualizar la canción: " . $conn->error;
    }
}
?>