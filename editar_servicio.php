<?php
include "conexion.php";

if ($conexion !== null) {
    // Verifica si se proporcionó el ID del servicio y los nuevos datos
    if (isset($_POST['idServicio'], $_POST['nuevoNombre'], $_POST['nuevoColor'], $_POST['nuevoIcono'])) {
        // Obtiene los datos desde la solicitud POST
        $idServicio = $_POST['idServicio'];
        $nuevoNombre = $_POST['nuevoNombre'];
        $nuevoColor = $_POST['nuevoColor'];
        $nuevoIcono = $_POST['nuevoIcono'];

        // Consulta para actualizar el servicio
        $sql = "UPDATE servicios SET nombre = '$nuevoNombre', color = '$nuevoColor', icono = '$nuevoIcono' WHERE id = $idServicio";

        if ($conexion->query($sql) === TRUE) {
            echo "Servicio editado en la base de datos";
        } else {
            echo "Error al editar el servicio: " . $conexion->error;
        }
    } else {
        // Si no se proporcionaron todos los datos necesarios, muestra un mensaje de error
        echo "Error: Datos incompletos para editar el servicio";
    }

    $conexion->close();
} else {
    echo "Error: Conexión a la base de datos no establecida";
}
?>
