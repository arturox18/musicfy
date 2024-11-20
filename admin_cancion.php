<?php
// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "musica");

// Verifica la conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Manejar eliminación de canciones
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['eliminar_cancion'])) {
    $pk_cancion = intval($_POST['pk_cancion']);

    // Consulta para eliminar la canción
    $eliminar_query = "DELETE FROM cancion WHERE pk_cancion = ?";
    $stmt = $conexion->prepare($eliminar_query);
    $stmt->bind_param("i", $pk_cancion);

    if ($stmt->execute()) {
        echo "<script>alert('Canción eliminada exitosamente.'); window.location.href = window.location.href;</script>";
    } else {
        echo "<script>alert('Error al eliminar la canción.');</script>";
    }
    $stmt->close();
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
      min-height: 100vh;
      margin: 0;
    }
    .song-list {
      width: 400px;
      background-color: #fff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
      text-align: center;
    }
    .song-item {
      padding: 10px 0;
      border-bottom: 1px solid #ddd;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .song-item:last-child {
      border-bottom: none;
    }
    .song-name {
      flex: 1;
      text-align: left;
    }
    .song-actions {
      text-align: right;
    }
    .delete-button {
      background-color: #e74c3c;
      color: white;
      border: none;
      border-radius: 5px;
      padding: 5px 10px;
      cursor: pointer;
      font-size: 14px;
    }
    .delete-button:hover {
      background-color: #c0392b;
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
          <div class="song-name">
            <a href="reproductor.php?cancion=<?php echo urlencode($fila['pk_cancion']); ?>">
              <?php echo htmlspecialchars($fila['nombre']); ?>
            </a>
          </div>
          <div class="song-actions">
            <form method="POST" style="display: inline;">
              <input type="hidden" name="pk_cancion" value="<?php echo $fila['pk_cancion']; ?>">
              <button type="submit" name="eliminar_cancion" class="delete-button">Eliminar</button>
            </form>
          </div>
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
