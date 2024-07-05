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

// Obtener el id del servicio de la URL
$idServicio = isset($_GET['id_servicio']) ? intval($_GET['id_servicio']) : 0;

// Verificar si se obtuvo correctamente el id_servicio
if ($idServicio == 0) {
    echo json_encode(array('success' => false, 'message' => 'No se proporcionó un id_servicio válido.'));
    exit;
}

// Consultar las encuestas en la base de datos filtradas por id_servicio
$sql = "SELECT id, titulo, descripcion, fecha, periodo FROM encuestas WHERE id_servicio = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $idServicio);
$stmt->execute();
$result = $stmt->get_result();

if ($result) {
    $encuestas = array();
    while ($row = $result->fetch_assoc()) {
        $encuestas[] = array(
            'id' => $row['id'],
            'titulo' => $row['titulo'],
            'descripcion' => $row['descripcion'],
            'fecha' => $row['fecha'],
            'periodo' => $row['periodo']

        );
    }
    echo json_encode(array('success' => true, 'encuestas' => $encuestas));
} else {
    echo json_encode(array('success' => false, 'message' => 'Error al obtener las encuestas de la base de datos: ' . $conn->error));
}

// Cerrar la conexión a la base de datos
$stmt->close();
$conn->close();
