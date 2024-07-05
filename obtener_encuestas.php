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

// Obtener el id_servicio del parámetro GET
$idServicio = isset($_GET['id_servicio']) ? $_GET['id_servicio'] : null;
// Obtener el periodo del parámetro GET
$periodo = isset($_GET['periodo']) ? $_GET['periodo'] : 'todos';

// Consultar las encuestas en la base de datos filtradas por id_servicio y/o periodo
if ($periodo === 'todos') {
    if ($idServicio) {
        $sql = "SELECT * FROM encuestas WHERE id_servicio = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $idServicio);
    } else {
        $sql = "SELECT * FROM encuestas";
        $stmt = $conn->prepare($sql);
    }
} else {
    if ($idServicio) {
        $sql = "SELECT * FROM encuestas WHERE id_servicio = ? AND periodo = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('is', $idServicio, $periodo);
    } else {
        $sql = "SELECT * FROM encuestas WHERE periodo = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s', $periodo);
    }
}

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
?>
