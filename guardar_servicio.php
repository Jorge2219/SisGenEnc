<?php
session_start();
include "conexion.php";

$nombre = isset($_POST['nombre']) ? $_POST['nombre'] : "";
$color = isset($_POST['color']) ? $_POST['color'] : "";
$icono = isset($_POST['icono']) ? $_POST['icono'] : "";

$response = []; // Array para almacenar la respuesta

if ($conexion !== null) {
    // Verificar si hay un usuario iniciado sesión
    if (isset($_SESSION['usuario_id'])) {
        $creador_id = $_SESSION['usuario_id'];
        $tipo_area = $_SESSION['area'];  // Agregar esta línea para obtener el tipo de área del usuario

        $sql = "INSERT INTO servicios (nombre, color, icono, creador_id, tipo_area) 
                VALUES ('$nombre', '$color', '$icono', '$creador_id', '$tipo_area')";

        if ($conexion->query($sql) === TRUE) {
            $response['status'] = 'success';
            $response['message'] = 'Servicio guardado en la base de datos';
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Error al guardar en la base de datos: ' . $conexion->error;
        }

        // Consulta para obtener todos los servicios
        $consultaServicios = "SELECT * FROM servicios";
        $result = $conexion->query($consultaServicios);

        // Guarda los servicios en un array para enviarlos al cliente
        $servicios = [];
        while ($row = $result->fetch_assoc()) {
            $servicios[] = $row;
        }

        $response['servicios'] = $servicios;
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Usuario no autenticado';
    }

    $conexion->close();
} else {
    $response['status'] = 'error';
    $response['message'] = 'Error: Conexión a la base de datos no establecida';
}

// Enviar la respuesta al cliente en formato JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
