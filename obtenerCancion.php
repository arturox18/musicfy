<?php
// archivo obtenerCancion.php
include 'conexion.php'; // Conecta a tu base de datos

// Selecciona una canción aleatoria, usando el campo `nombre` y `cancion` para la ruta del archivo
$sql = "SELECT nombre, cancion FROM cancion ORDER BY RAND() LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $cancion = $result->fetch_assoc();
    
    // Crea la ruta completa del archivo de audio, usando la carpeta 'uploads' y el nombre de archivo
    $cancion['ruta'] = "uploads/" . $cancion['cancion'];

    echo json_encode($cancion);
} else {
    echo json_encode(["error" => "No se encontró ninguna canción."]);
}

$conn->close();
?>
