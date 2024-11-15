<?php
// Conexión a la base de datos
include 'conexion.php'; // Asegúrate de que contiene los datos correctos de conexión

// Arreglo para almacenar las opciones
$canciones = [];

// Obtener tres nombres aleatorios de la tabla opciones (falsas)
$sqlOpciones = "SELECT nombre_opcion FROM opciones ORDER BY RAND() LIMIT 3";
$resultOpciones = $conn->query($sqlOpciones);

if ($resultOpciones->num_rows > 0) {
    while ($row = $resultOpciones->fetch_assoc()) {
        $canciones[] = [
            "nombre" => $row['nombre_opcion'],
            "tipo" => "falsa",
            "audio" => "" // Sin archivo de audio para las opciones falsas
        ];
    }
}

// Obtener un nombre aleatorio de la tabla cancion (verdadera)
$sqlCancion = "SELECT nombre, cancion FROM cancion ORDER BY RAND() LIMIT 1";
$resultCancion = $conn->query($sqlCancion);

if ($resultCancion->num_rows > 0) {
    $row = $resultCancion->fetch_assoc();
    $ruta = "./uploads/" . $row['cancion'];
    $canciones[] = [
        "nombre" => $row['nombre'],
        "tipo" => "verdadera",
        "audio" => $ruta // Aquí se agrega la URL del audio de la canción verdadera
    ];
} else {
    echo json_encode(["error" => "No se encontró ninguna canción verdadera."]);
    $conn->close();
    exit;
}

// Mezclar las canciones (opciones falsas y verdadera)
shuffle($canciones);

// Retornar el resultado en formato JSON
echo json_encode($canciones);

$conn->close();
?>
