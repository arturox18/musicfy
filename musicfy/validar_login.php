<?php
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
    $nom_usuario = $conn->real_escape_string($_POST['nom_usuario']);
    $pw = $conn->real_escape_string($_POST['pw']);

    
    $sql = "SELECT * FROM usuario WHERE nom_usuario = '$nom_usuario' AND pw = '$pw' AND estatus = 1";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc(); // Obtener los datos del usuario
        $usuario_id = $row['pk_usuario']; // Almacenar el ID del usuario
            session_start();
            $_SESSION['pk_usuario'] = $usuario_id; // Guardar el ID del usuario en la sesión
            header("Location: index_pro.php"); // Redirigir a la página deseada
            exit(); // Detener la ejecución del script
        } else {
            echo "Usuario o contraseña incorrectos";
        }
    } else {
        echo "Usuario o contraseña incorrectos";
    }
    
$conn->close();
?>