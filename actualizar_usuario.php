<?php
$nombre_usuario = $_POST["nom_usuario"];
$correo = $_POST["correo"];
$contraseña = $_POST["pw"];
$descripcion = $_POST["descripcion"];
$pk_usuario = $_POST["pk_usuario"];
$foto = $_FILES["foto"];

// Procesar la foto si se subió
$nombre_foto = null;
if ($foto["error"] == UPLOAD_ERR_OK) {
    $nombre_foto = "fotos/" . basename($foto["name"]);
    move_uploaded_file($foto["tmp_name"], $nombre_foto);
}

include("clase_usuario.php");
$usuario = new Usuario();

$respuesta = $usuario->actualizar($nombre_usuario, $correo, $contraseña, $descripcion, $pk_usuario, $nombre_foto);

echo $respuesta;
?>
