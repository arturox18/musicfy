<?php
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Escapar los valores ingresados para evitar SQL Injection
    $nom_usuario = $conn->real_escape_string($_POST['nom_usuario']);
    $pw = $conn->real_escape_string($_POST['pw']);

    // Consulta SQL para verificar el usuario y la contraseña
    $sql = "SELECT * FROM usuario WHERE nom_usuario = '$nom_usuario' AND pw = '$pw' AND estatus = 1";
    $result = $conn->query($sql);

    if ($result && $result->num_rows == 1) {
        // Redirigir a pantalla.html si la autenticación es correcta
        header("Location: pantalla.html");
        exit();
    } else {
        // Mostrar mensaje de error si la autenticación falla
        echo "Usuario o contraseña incorrectos";
    }
}

// Cerrar la conexión a la base de datos
$conn->close();
?>
