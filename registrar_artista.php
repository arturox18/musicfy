<?php
// Inicia la sesión
session_start();

// Incluye el archivo de conexión a la base de datos
include 'conexion.php';

// Verifica que el usuario esté autenticado y que `pk_usuario` esté en la sesión
if (isset($_SESSION['pk_usuario'])) {
    $fk_usuario = $_SESSION['pk_usuario']; // Obtiene el ID del usuario logueado
} else {
    die("Error: Usuario no autenticado.");
}

// Verifica que el método de solicitud sea POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibe y sanitiza los datos enviados desde el formulario
    $bibliografia = mysqli_real_escape_string($conn, $_POST['bibliografia']);
    $genero = mysqli_real_escape_string($conn, $_POST['genero']);
    $pais = mysqli_real_escape_string($conn, $_POST['pais']);

    // Prepara la consulta de inserción
    $sql = "INSERT INTO artista (bibliografia, genero, pais, fk_usuario) VALUES ('$bibliografia', '$genero', '$pais', '$fk_usuario')";

    // Ejecuta la consulta y verifica si fue exitosa
    if (mysqli_query($conn, $sql)) {
        echo "Artista registrado exitosamente.";
        header("Location: index2.php"); // Redirige a otra página si el registro es exitoso
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn); // Muestra el error si hay un problema
    }

    // Cierra la conexión a la base de datos
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="diseño.css">
    <title>Registro de Artistas</title>
</head>
<body>
    <div class="login-container">

        <h2>Registrar Artista</h2>
        <form action="registrar_artista.php" method="POST">
            
            <label for="bibliografia">Bibliografía:</label>
            <textarea id="bibliografia" name="bibliografia" rows="4" cols="50" required></textarea>
            <br><br>
           
            <label for="genero">Género Musical:</label>
            <input type="text" id="genero" name="genero" required>
            <br><br>
            
            <label for="pais">País de Origen:</label>
            <input type="text" id="pais" name="pais" required>
            <br><br>
          
            <!-- Campo oculto para el ID del usuario logueado -->
            <input type="hidden" id="fk_usuario" name="fk_usuario" value="<?= $fk_usuario ?>">

            <!-- Cambiar tipo de botón a submit -->
            <button type="submit">Registrar Artista</button>
            <a href="index2.php"><button type="button" style="margin-top: 10px;">Cancelar</button></a>

        </form>

    </div>
</body>
</html>
