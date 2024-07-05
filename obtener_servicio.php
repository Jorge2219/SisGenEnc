<?php
include "conexion.php";

if ($conexion !== null) {
    // Consulta para obtener todos los servicios
    $consultaServicios = "SELECT * FROM servicios";
    $result = $conexion->query($consultaServicios);

    // Guarda los servicios en un array para enviarlos al cliente
    $servicios = [];
    while ($row = $result->fetch_assoc()) {
        // Incluye el ID del servicio en el array
        $servicio = [
            'id' => $row['id'],
            'nombre' => $row['nombre'],
            'color' => $row['color'],
            'icono' => $row['icono']
        ];
        $servicios[] = $servicio;
    }

    // Envía los servicios al cliente en formato JSON
    echo json_encode($servicios);

    $conexion->close();
} else {
    echo "Error: Conexión a la base de datos no establecida";
}
?>
