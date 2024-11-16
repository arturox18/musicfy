<?php
include 'conexion.php';

// Consulta para obtener la información combinada de artista, cancion y album
$sql = "SELECT 
            artista.bibliografia AS artista_bibliografia,
            artista.genero AS artista_genero,
            artista.pais AS artista_pais,
            usuario.nom_usuario AS usuario_nombre,
            cancion.nombre AS cancion_nombre,
            cancion.cancion AS archivo_cancion,
            cancion.duracion AS cancion_duracion,
            cancion.genero AS cancion_genero,
            album.titulo AS album_titulo,
            album.fecha AS album_fecha,
            album.caratula AS album_caratula
        FROM cancion
        INNER JOIN album ON cancion.fk_album = album.pk_album
        INNER JOIN artista ON album.fk_artista = artista.pk_artista
        INNER JOIN usuario ON artista.fk_usuario = usuario.pk_usuario";
    

$result = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Artistas, Canciones y Álbumes</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #e0f7fa;
            color: #37474f;
            margin: 0;
            padding: 0;
        }
        .navbar {
            background-color: #00838f;
            color: white;
            padding: 20px;
            text-align: center;
            font-size: 1.5em;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        }
        .container {
            margin: 30px;
            display: flex;
            justify-content: center;
        }
        table {
            width: 100%;
            max-width: 1000px;
            border-collapse: separate;
            border-spacing: 0;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0px 6px 12px rgba(0, 0, 0, 0.15);
            background-color: #ffffff;
        }
        th, td {
            padding: 15px;
            text-align: left;
        }
        th {
            background-color: #00796b;
            color: #ffffff;
            font-weight: bold;
            text-transform: uppercase;
        }
        td {
            border-bottom: 1px solid #cfd8dc;
        }
        tr:last-child td {
            border-bottom: none;
        }
        tr:hover {
            background-color: #e0f2f1;
        }
        img {
            max-width: 60px;
            height: auto;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>Listado de Artistas, Canciones y Álbumes</h1>
    </div>

    <div class="container">
        <table>
            <thead>
                <tr>
                    <th>artista</th>
                    <th>Bibliografia</th>
                    <th>Género del Artista</th>
                    <th>País del Artista</th>
                    <th>Canción</th>
                    <th>Duración</th>
                    <th>Género de la Canción</th>
                    <th>Álbum</th>
                    <th>Fecha del Álbum</th>
                    <th>Carátula</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['usuario_nombre'] . "</td>";
                        echo "<td>" . $row['artista_bibliografia'] . "</td>";
                        echo "<td>" . $row['artista_genero'] . "</td>";
                        echo "<td>" . $row['artista_pais'] . "</td>";
                        echo "<td>" . $row['cancion_nombre'] . "</td>";
                        echo "<td>" . $row['cancion_duracion'] . "</td>";
                        echo "<td>" . $row['cancion_genero'] . "</td>";
                        echo "<td>" . $row['album_titulo'] . "</td>";
                        echo "<td>" . $row['album_fecha'] . "</td>";
                        echo "<td><img src='" . $row['album_caratula'] . "' alt='Carátula'></td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='9' style='text-align: center;'>No hay datos disponibles</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
