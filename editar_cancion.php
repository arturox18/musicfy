<?php
if (!isset($_GET["pk_cancion"])) {
    die("ID de canción no especificado.");
}
$pk_cancion = $_GET["pk_cancion"];

include("clase_cancion.php");
$cancion = new Cancion();
$datos_cancion = $cancion->obtenerCancion($pk_cancion);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Editar Canción</title>
    <link rel="stylesheet" href="diseño.css">
</head>
<body>
    <div class="login-container"> 
        <h3>Editar Canción</h3>
        <form action="actualizar_cancion.php" method="post" enctype="multipart/form-data">
            <label>Nombre de Canción:</label><br>
            <input value="<?= $datos_cancion["nombre"] ?>" type="text" name="nombre" required><br><br>

            <label>Género:</label><br>
            <input value="<?= $datos_cancion["genero"] ?>" type="text" name="genero" required><br><br>

            <label>Archivo de Audio (MP3, WAV, OGG):</label><br>
            <input type="file" name="audio"><br><br>

            <label>Álbum:</label><br>
            <select name="fk_album" required>
                <?php
                // Asegúrate de que tienes una lista de álbumes para mostrar aquí
                include("clase_album.php");
                $album = new Album(); // Aquí asumo que tienes una clase para manejar álbumes
                $albumes = $album->obtenerAlbumes(); // Método que retorna todos los álbumes
                foreach ($albumes as $a) {
                    // Si el álbum actual es el que se está editando, selecciónalo
                    $selected = ($a["pk_album"] == $datos_cancion["fk_album"]) ? "selected" : "";
                    echo "<option value='{$a["pk_album"]}' $selected>{$a["nombre_album"]}</option>";
                }
                ?>
            </select><br><br>

            <input value="<?= $datos_cancion["pk_cancion"] ?>" type="hidden" name="pk_cancion">
            
            <button id="boton" type="submit">Guardar</button>
            <a id="boton" href="panel_cancion.php">Cancelar</a>
        </form>
    </div>
</body>
</html>
