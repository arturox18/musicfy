<?php
// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "musica");

// Verifica la conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Consulta para obtener las canciones y su nombre
$query = "SELECT pk_cancion, nombre FROM cancion";
$resultado = $conexion->query($query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Listado de Canciones</title>
  <style>
    /* Estilos básicos para la página de listado */
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f9;
      color: #333;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }
    .song-list {
      width: 300px;
      background-color: #fff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
      text-align: center;
    }
    .song-item {
      padding: 10px 0;
      border-bottom: 1px solid #ddd;
    }
    .song-item:last-child {
      border-bottom: none;
    }
    a {
      color: #5c2a7a;
      text-decoration: none;
    }
    a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="song-list">
    <h2>Listado de Canciones</h2>
    <?php if ($resultado->num_rows > 0): ?>
      <?php while ($fila = $resultado->fetch_assoc()): ?>
        <div class="song-item">
          <a href="reproductor.php?cancion=<?php echo urlencode($fila['pk_cancion']); ?>">
            <?php echo htmlspecialchars($fila['nombre']); ?>
          </a>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p>No hay canciones disponibles.</p>
    <?php endif; ?>
  </div>

  <?php
  // Cierra la conexión a la base de datos
  $conexion->close();
  ?>
</body>
</html>
