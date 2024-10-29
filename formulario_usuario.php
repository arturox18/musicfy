<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "musica";
 
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
$nombre = $conn->real_escape_string($_POST['nombre']);
$apaterno = $conn->real_escape_string($_POST['apaterno']);
$amaterno = $conn->real_escape_string($_POST['amaterno']);
$sexo = $conn->real_escape_string($_POST['sexo']);

$sql_persona = "INSERT INTO persona (nombre, apaterno, amaterno, sexo) VALUES ('$nombre', '$apaterno', '$amaterno', '$sexo')";

if ($conn->query($sql_persona) === TRUE) {
    $persona_id = $conn->insert_id;

    $nom_usuario = $conn->real_escape_string($_POST['nom_usuario']);
    $correo = $conn->real_escape_string($_POST['correo']);
    $pw = $conn->real_escape_string($_POST['pw']);

    $sql_usuario = "INSERT INTO usuario (nom_usuario, correo, pw, fk_persona) VALUES ('$nom_usuario', '$correo', '$pw', '$persona_id')";

    if ($conn->query($sql_usuario) === TRUE) {
        echo "Usuario registrado correctamente.";
    } else {
        echo "Error al registrar en la tabla usuario: " . $conn->error;
    }
} else {
    echo "Error al registrar en la tabla persona: " . $conn->error;
}
$conn->close();
?>
