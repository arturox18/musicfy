<?php 
include 'conexion.php'; // Asegúrate de que esta línea está al inicio del archivo
if (!class_exists('conexion')) {
    die("La clase 'conexion' no se pudo encontrar.");
}
class usuario {
    private $conexion;

    public function __construct() {
        $this->conexion = new conexion(); // Crea una nueva instancia de la clase conexion
    }

    public function actualizar($nombre_usuario, $correo, $contraseña, $descripcion, $idusuario, $foto) {
        // Si hay una foto nueva, inclúyela en la actualización
        $foto_query = $foto ? ", foto = '$foto'" : "";
        
        $query = "UPDATE usuario SET 
                    nom_usuario = '$nombre_usuario',
                    correo = '$correo',
                    pw = '$contraseña',
                    descripcion = '$descripcion'
                    $foto_query
                  WHERE pk_usuario = '$idusuario'";
        
        $result = mysqli_query($this->conn, $query);
        return $result ? "Usuario actualizado exitosamente." : "Error al actualizar el usuario.";
    }
}
    ?>
