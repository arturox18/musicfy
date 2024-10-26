<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "jc_seguridad";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$nick_name = $_POST['nick_name'];
$contraseña = $_POST['contraseña'];

$sql = "SELECT * FROM usuario WHERE nick_name = '$nick_name'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $usuario = $result->fetch_assoc();

    if (password_verify($contraseña, $usuario['contraseña'])) {
        echo "Inicio de sesión exitoso";
    } else {
        echo "Contraseña incorrecta";
    }
} else {
    echo "El usuario no existe";
}
$conn->close();