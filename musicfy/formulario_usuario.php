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

    $estatus = isset($_POST['estatus']) ? $_POST['estatus'] : 1;

    $sql_usuario = "INSERT INTO usuario (nom_usuario, correo, pw, estatus, fk_persona) VALUES ('$nom_usuario', '$correo', '$pw', '$estatus', '$persona_id')";
   
    if ($conn->query($sql_usuario) === TRUE) {
        echo "Usuario registrado correctamente.";
        
        header("Location: inicio_sesion.html");
        exit(); // Asegura que el script termine aquí
    } else {
        echo "Error al registrar en la tabla usuario: " . $conn->error;
    }
} else {
    echo "Error al registrar en la tabla persona: " . $conn->error;
}

$conn->close();
