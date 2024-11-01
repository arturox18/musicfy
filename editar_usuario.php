<?php
if (!isset($_GET["pk_usuario"])) {
    die("ID de usuario no especificado.");
}
$pk_usuario = $_GET["pk_usuario"];

include("clase_usuario.php");
$usuario = new Usuario();
$datos_usuario = $usuario->buscar($pk_usuario);
$datos = mysqli_fetch_assoc($datos_usuario);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Editar Usuario</title>
    <link rel="stylesheet" href="diseño.css">
</head>
<body>
    <div class="login-container"> 
    <h3>Editar Usuario</h3>
    <form action="actualizar_usuario.php" method="post" enctype="multipart/form-data">
        <label>Nombre de Usuario:</label><br>
        <input value="<?= $datos["nom_usuario"] ?>" type="text" name="nom_usuario"><br><br>
        
        <label>Foto de Perfil:</label><br>
        <input type="file" name="foto"><br><br>
        
        <label>Correo:</label><br>
        <input value="<?= $datos["correo"] ?>" type="email" name="correo"><br><br>

        <label>Contraseña:</label><br>
        <input value="<?= $datos["pw"] ?>" type="password" name="pw"><br><br>
        
        <label>Descripción:</label><br>
        <textarea name="descripcion"><?= $datos["descripcion"] ?></textarea><br><br>

        <input value="<?= $datos["pk_usuario"] ?>" type="hidden" name="pk_usuario">
        
        <button id= "boton" type="submit"> Guardar </button>
        <a href="panel_usuario.php" class="boton-cancelar">Cancelar</a>    
    </form>
    </div>
</body>
</html>
