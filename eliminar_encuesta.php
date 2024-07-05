<?php
header('Content-Type: application/json');

$host = 'localhost';
$db = 'id22332073_login';
$user = 'id22332073_root';
$pass = 'Diego&23';

// Intentar conectar a la base de datos
$conn = new mysqli($host, $user, $pass, $db);

// Verificar si hay errores en la conexión
if ($conn->connect_error) {
    echo json_encode(array('success' => false, 'message' => 'Error de conexión a la base de datos: ' . $conn->connect_error));
    exit;
}

// Obtener el ID de la encuesta a eliminar
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

if ($id > 0) {
    // Eliminar la encuesta de la base de datos
    $sql = "DELETE FROM encuestas WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
        echo json_encode(array('success' => true, 'message' => 'Encuesta eliminada exitosamente'));
    } else {
        echo json_encode(array('success' => false, 'message' => 'Error al eliminar la encuesta: ' . $conn->error));
    }
} else {
    echo json_encode(array('success' => false, 'message' => 'ID de encuesta no válido'));
}

// Cerrar la conexión a la base de datos
$conn->close();
