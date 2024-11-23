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
        header("Location: index_pro.php");
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }

    // Cierra la conexión a la base de datos
    mysqli_close($conn);
}
?>
