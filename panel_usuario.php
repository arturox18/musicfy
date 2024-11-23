<?php
session_start();
include("clase_usuario.php");

$usuario = new usuario();
$idusuario = $_SESSION['pk_usuario']; // Asegúrate de que el ID del usuario esté en la sesión

// Obtén los datos del usuario.
$user_data = $usuario->obtener_usuario($idusuario); // Llama al método que agregaste

// Verifica que los datos del usuario estén disponibles
if ($user_data) {
    $nombre_usuario = $user_data['nom_usuario']; // Cambia esto según tus columnas
    $correo = $user_data['correo'];
    $descripcion = $user_data['descripcion'];
    $foto = $user_data['foto']; // Nombre de la foto
} else {
    // Manejo de errores si no se encuentra al usuario
    die("Error: Usuario no encontrado.");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Usuario</title>
    <link rel="stylesheet" href="css/diseño_panel.css"> 
</head>
<body>
    <div class="panel"> 

        <h2>Perfil de Usuario</h2>
        <div class="perfil">
            <img src="imagenes/<?= htmlspecialchars($foto) ?>" alt="Foto de perfil" class="foto-perfil">
            <h3><?= htmlspecialchars($nombre_usuario) ?></h3>
            <p><strong>Correo:</strong> <?= htmlspecialchars($correo) ?></p>
            <p><strong>Descripción:</strong> <?= nl2br(htmlspecialchars($descripcion)) ?></p>
            <form action="editar_usuario.php" method="get">
                <input type="hidden" name="pk_usuario" value="<?= htmlspecialchars($idusuario) ?>">
                <a href="index_pro.php" class="boton-volver">volver</a> 
                <input type="submit" value="Editar" class="boton-editar">
            </form>
        </div>
    </div>
</body>
</html>
