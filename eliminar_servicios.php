<?php
include "conexion.php";

if ($conexion !== null) {
    // Verifica si se proporcionó el ID del servicio a eliminar
    if (isset($_POST['idServicio'])) {
        // Obtiene el ID del servicio desde la solicitud POST
        $idServicio = $_POST['idServicio'];

        // Consulta para eliminar el servicio específico
        $sql = "DELETE FROM servicios WHERE id = $idServicio";

        if ($conexion->query($sql) === TRUE) {
            echo "Servicio eliminado de la base de datos";
        } else {
            echo "Error al eliminar el servicio: " . $conexion->error;
        }
    } else {
        // Si no se proporcionó el ID del servicio, muestra un mensaje de error
        echo "Error: ID de servicio no proporcionado";
    }

    $conexion->close();
} else {
    echo "Error: Conexión a la base de datos no establecida";
}
?>
