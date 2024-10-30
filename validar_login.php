<?php
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
    $nom_usuario = $conn->real_escape_string($_POST['nom_usuario']);
    $pw = $conn->real_escape_string($_POST['pw']);

    
    $sql = "SELECT * FROM usuario WHERE nom_usuario = '$nom_usuario' AND pw = '$pw' AND estatus = 1";
    $result = $conn->query($sql);

    if ($result && $result->num_rows == 1) {
       
        header("Location: pantalla.html");
        exit();
    } else {
       
        echo "Usuario o contraseña incorrectos";
    }
}


$conn->close();
?>
