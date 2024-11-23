<?php

session_start();
$conexion = new mysqli("localhost", "root", "", "musica");

// Verificar conexión
if ($conexion->connect_error) {
    die("Error en la conexión: " . $conexion->connect_error);
}

// Obtener el término de búsqueda
$q = $_GET['q'] ?? '';
$q = '%' . $conexion->real_escape_string($q) . '%'; // Agregar comodines para búsqueda

// Preparar la consulta SQL
$stmt = $conexion->prepare("
     SELECT c.pk_cancion, c.nombre AS cancion_nombre, u.nom_usuario AS artista_nombre, al.titulo AS album_nombre
FROM cancion c
LEFT JOIN album al ON c.fk_album = al.pk_album
LEFT JOIN artista ar ON al.fk_artista = ar.pk_artista
LEFT JOIN usuario u ON ar.fk_usuario = u.pk_usuario
WHERE c.nombre LIKE ? OR u.nom_usuario LIKE ? OR al.titulo LIKE ?
ORDER BY c.nombre ASC;

");

// Vincular parámetros
$stmt->bind_param("sss", $q, $q, $q);

// Ejecutar consulta
$stmt->execute();

// Obtener resultados
$resultado = $stmt->get_result();

// Mostrar resultados
if ($resultado->num_rows > 0) {
    while ($row = $resultado->fetch_assoc()) {
        echo "<p><strong>Canción:</strong> " . htmlspecialchars($row["cancion_nombre"]) . "<br>";
        echo "<strong>Artista:</strong> " . htmlspecialchars($row["artista_nombre"]) . "<br>";
        echo "<strong>Álbum:</strong> " . htmlspecialchars($row["album_nombre"]) . "</p>";
    }
} else {
    echo "No se encontraron resultados para '$q'";
}

// Cerrar conexión
$stmt->close();
$conexion->close();
