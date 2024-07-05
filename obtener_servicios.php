<?php
error_reporting(E_ALL);
session_start();

include "conexion.php";

if ($conexion !== null) {
    // Verificar si el usuario es administrador
    $esAdmin = false;

    // Obtener el valor de $_SESSION['area']
    $tipoAreaUsuario = isset($_SESSION['area']) ? strtolower($_SESSION['area']) : '';

    // Si el tipo de área es "admin" o "administrador", mostrar todos los servicios
    if ($tipoAreaUsuario == 'admin' || $tipoAreaUsuario == 'administrador') {
        $consultaServicios = "SELECT * FROM servicios";
    } else {
        // Si no es administrador, obtener el área del usuario de la sesión
        $creador_id = isset($_SESSION['usuario_id']) ? $_SESSION['usuario_id'] : 0;

        // 's' es un alias para la tabla servicios
        $consultaServicios = "SELECT * FROM servicios WHERE tipo_area = '$tipoAreaUsuario'";
    }

    // Ejecutar la consulta
    $resultServicios = $conexion->query($consultaServicios);

    if (!$resultServicios) {
        echo "Error en la consulta de servicios: " . $conexion->error;
        exit;
    }

    // Guardar los servicios en un array para enviarlos al cliente
    $servicios = [];
    while ($row = $resultServicios->fetch_assoc()) {
        $servicios[] = $row;
    }

    // Envía los servicios al cliente en formato JSON
    echo json_encode($servicios);

    $conexion->close();
} else {
    echo "Error: Conexión a la base de datos no establecida";
}
?>
