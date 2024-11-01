<?php
include("clase_usuario.php");
$usuario = new usuario();

// Asumir que estos datos vienen de un formulario
$nombre_usuario = $_POST['nom_usuario']; // Suponiendo que este es el campo para el nombre
$correo = $_POST['correo'];
$contraseña = $_POST['pw'];
$descripcion = $_POST['descripcion'];
$idusuario = $_POST['pk_usuario'];
$foto = $_FILES['foto']['name']; // Si estás subiendo una foto

// Lógica para subir la foto
if (!empty($foto)) {
    // Mover la foto a la carpeta deseada
    move_uploaded_file($_FILES['foto']['tmp_name'], "imagenes/$foto");
}

$respuesta = $usuario->actualizar($nombre_usuario, $correo, $contraseña, $descripcion, $idusuario, $foto);
echo $respuesta;
?>
