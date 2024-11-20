<?php
// Conexión a la base de datos
$conexion = new mysqli("localhost", "root", "", "musica");

// Verifica la conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

// Manejar eliminación de usuarios
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['eliminar_usuario'])) {
    $pk_usuario = intval($_POST['pk_usuario']);

    // Consulta para eliminar el usuario
    $eliminar_query = "DELETE FROM usuario WHERE pk_usuario = ?";
    $stmt = $conexion->prepare($eliminar_query);
    $stmt->bind_param("i", $pk_usuario);

    if ($stmt->execute()) {
        echo "<script>alert('Usuario eliminado exitosamente.'); window.location.href = window.location.href;</script>";
    } else {
        echo "<script>alert('Error al eliminar el usuario.');</script>";
    }
    $stmt->close();
}

// Consulta para obtener los usuarios y sus nombres
$query = "SELECT pk_usuario, nom_usuario FROM usuario";
$resultado = $conexion->query($query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Listado de Usuarios</title>
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
    .user-list {
      width: 400px;
      background-color: #fff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
      text-align: center;
    }
    .user-item {
      padding: 10px 0;
      border-bottom: 1px solid #ddd;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .user-item:last-child {
      border-bottom: none;
    }
    .user-name {
      flex: 1;
      text-align: left;
    }
    .user-actions {
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
  <div class="user-list">
    <h2>Listado de Usuarios</h2>
    <?php if ($resultado->num_rows > 0): ?>
      <?php while ($fila = $resultado->fetch_assoc()): ?>
        <div class="user-item">
          <div class="user-name">
            <?php echo htmlspecialchars($fila['nom_usuario']); ?>
          </div>
          <div class="user-actions">
            <form method="POST" style="display: inline;">
              <input type="hidden" name="pk_usuario" value="<?php echo $fila['pk_usuario']; ?>">
              <button type="submit" name="eliminar_usuario" class="delete-button">Eliminar</button>
            </form>
          </div>
        </div>
      <?php endwhile; ?>
    <?php else: ?>
      <p>No hay usuarios disponibles.</p>
    <?php endif; ?>
  </div>

  <?php
  // Cierra la conexión a la base de datos
  $conexion->close();
  ?>
</body>
</html>
