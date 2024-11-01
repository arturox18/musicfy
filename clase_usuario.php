<?php 
// Incluyendo la conexión a la base de datos
include 'conexion.php'; // Asegúrate de que esta línea está al inicio del archivo

class usuario {
    // No necesitas una propiedad para la conexión, ya que usarás la variable global directamente

    public function buscar($pk_usuario) {
        global $conn; // Usar la conexión globalmente
        $sql = "SELECT * FROM usuario WHERE pk_usuario = $pk_usuario";
        return $conn->query($sql);
    }

    public function obtener_usuario($pk_usuario) {
        global $conn; // Usar la conexión globalmente
        $sql = "SELECT * FROM usuario WHERE pk_usuario = $pk_usuario";
        return $conn->query($sql)->fetch_assoc(); // Obtener solo una fila
    }
    public function actualizar($nombre_usuario, $correo, $contraseña, $descripcion, $idusuario, $foto) {
        global $conn; // Usar la conexión globalmente
        
        // Si hay una foto nueva, inclúyela en la actualización
        $foto_query = $foto ? ", foto = '$foto'" : "";
        
        // Asegúrate de que los valores estén correctamente escapados
        $query = "UPDATE usuario SET 
                    nom_usuario = '$nombre_usuario',
                    correo = '$correo',
                    pw = '$contraseña',
                    descripcion = '$descripcion'
                    $foto_query
                  WHERE pk_usuario = '$idusuario'";
        
        $result = $conn->query($query); // Usar $conn directamente
        return $result ? "Usuario actualizado exitosamente." : "Error al actualizar el usuario: " . $conn->error; // Agregar error de conexión si falla
    }
}
?>
