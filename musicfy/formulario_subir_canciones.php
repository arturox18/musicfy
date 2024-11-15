<?php
session_start(); // Iniciar sesión

// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "musica";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Verifica si el usuario está conectado
if (isset($_SESSION['pk_usuario'])) {
    $id_usuario = $_SESSION['pk_usuario'];

    // Consulta para obtener los álbumes del usuario
    $sql = "
        SELECT a.pk_album AS pk_album, a.titulo AS titulo 
        FROM album a 
        JOIN artista ar ON a.fk_artista = ar.pk_artista 
        WHERE ar.fk_usuario = ?
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    echo "No hay usuario en la sesión.";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="diseño.css">
    <title>SUBIR CANCIONES</title>
</head>
<body>
    <div class="login-container">
        <h2>SUBIR CANCIONES</h2>

        <form action="subir_canciones.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nombre">Nombre de la canción</label>
                <input type="text" name="nombre" placeholder="Nombre" required>
            </div>

            <div class="butom">
                <label for="genero">Género Musical</label>
                <input type="text" name="genero" placeholder="Género" required>
            </div>
            
            <div class="button">
                <label for="duracion">Duración</label>
                <input type="text" name="duracion" placeholder="Duración (min)" required>
            </div>

            <div class="select">
                <label for="albumes">Selecciona un álbum:</label>
                <select name="album" id="albumes" required>
                    <?php
                    // Si el usuario está conectado y tiene álbumes
                    if (isset($result) && $result->num_rows > 0) {
                        // Salida de datos de cada fila
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row["pk_album"] . "'>" . htmlspecialchars($row["titulo"]) . "</option>";
                        }
                    } else {
                        echo "<option value=''>No hay álbumes disponibles</option>";
                    }

                    // Cerrar conexión
                    $stmt->close();
                    $conn->close();
                    ?>
                </select>
            </div>

            <div class="button">
                <label for="audio">Canción</label>
                <input type="file" name="audio" accept="audio/*" required>
            </div>

            <button type="submit">Subir Canción</button>
            <a href="index_pro.php"><button type="button" style="margin-top: 10px;">Cancelar</button></a>
        </form>
    </div>
</body>
</html>
