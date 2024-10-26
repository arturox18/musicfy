<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "jc_seguridad";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$nombre = $_POST['nombre'];
$apellido_paterno = $_POST['apellido_paterno'];
$apellido_materno = $_POST['apellido_materno'];
$nick_name = $_POST['nick_name'];
$contraseña = $_POST['contraseña'];

$contraseña_cifrada = password_hash($contraseña, PASSWORD_DEFAULT);

$sql = "INSERT INTO usuario (nombre, apellido_paterno, apellido_materno, nick_name, contraseña) VALUES ('$nombre', '$apellido_paterno',
 '$apellido_materno', '$nick_name', '$contraseña_cifrada')";

if ($conn->query($sql) === TRUE) {
    echo "Registro exitoso";
} else {
    echo "Error en la consulta SQL: " . $conn->error;
}
$conn->close();
?>
