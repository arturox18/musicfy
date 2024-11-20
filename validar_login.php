<?php
include 'conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Escapar entradas para evitar inyecciones SQL
    $nom_usuario = $conn->real_escape_string($_POST['nom_usuario']);
    $pw = $conn->real_escape_string($_POST['pw']);

    // Consulta para verificar el usuario y su rol
    $sql = "SELECT u.pk_usuario, u.nom_usuario, u.pw, tu.tipo_usuario AS rol
            FROM usuario u
            JOIN usuario_tipo_u utu ON u.pk_usuario = utu.fk_usuario
            JOIN tipo_usuario tu ON utu.fk_tipo_usuario = tu.pk_tipo_usuario
            WHERE u.nom_usuario = '$nom_usuario' AND u.pw = '$pw' AND u.estatus = 1";

    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc(); // Obtener los datos del usuario
        $usuario_id = $row['pk_usuario'];
        $rol = $row['rol']; // Obtener el rol del usuario

        // Iniciar sesión y guardar datos
        session_start();
        $_SESSION['pk_usuario'] = $usuario_id;
        $_SESSION['rol'] = $rol;

        // Redirigir según el rol del usuario
        if ($rol === 'Administrador') {
            header("Location: index_admin.php"); // Redirigir al panel de administrador
        } else {
            header("Location: index2.php"); // Redirigir al panel de usuario normal
        }
        exit(); // Detener la ejecución del script
    } else {
        echo "Usuario o contraseña incorrectos";
    }
}

$conn->close();
?>
