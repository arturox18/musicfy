<?php
// Iniciar sesión y conectar a la base de datos
session_start();
$conexion = new mysqli("localhost", "root", "", "musica");

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Obtener el término de búsqueda
$q = isset($_GET['q']) ? trim($_GET['q']) : '';

if (!empty($q)) {
    // Prevenir inyección SQL
    $q = $conexion->real_escape_string($q);

    // Buscar en canciones, artistas y álbumes
    $sql = "
        SELECT pk_cancion, nombre AS cancion_nombre, fk_album AS album_nombre
        FROM cancion 
        LEFT JOIN album a ON fk_album = a.pk_album
        
        WHERE nombre LIKE '%$q%' 
           
        LIMIT 20;
    ";
    $resultado = $conexion->query($sql);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados de Búsqueda</title>
    <link rel="stylesheet" href="diseno_index.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
</head>
<body>
    <h1>Resultados de búsqueda para "<?= htmlspecialchars($q) ?>"</h1>
    <div class="results">
        <?php if (!empty($q) && $resultado->num_rows > 0): ?>
            <?php while ($fila = $resultado->fetch_assoc()): ?>
                <div class="result-item">
                    <p><strong>Canción:</strong> <?= htmlspecialchars($fila['cancion_nombre']) ?></p>
                    <p><strong>Álbum:</strong> <?= htmlspecialchars($fila['album_nombre']) ?></p>
                    <p><strong>Artista:</strong> <?= htmlspecialchars($fila['artista_nombre']) ?></p>
                </div>
            <?php endwhile; ?>
        <?php elseif (!empty($q)): ?>
            <p>No se encontraron resultados para "<?= htmlspecialchars($q) ?>"</p>
        <?php else: ?>
            <p>Introduce un término de búsqueda.</p>
        <?php endif; ?>
    </div>
    <a href="index_pro.php" class="back-link">Volver</a>
</body>
</html>
<?php
$conexion->close();
?>
