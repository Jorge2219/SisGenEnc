<?php
header('Content-Type: application/json');

// Verificar si se recibieron datos JSON
$data = json_decode(file_get_contents('php://input'), true);

if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(array('success' => false, 'message' => 'Error en el JSON recibido: ' . json_last_error_msg()));
    exit;
}

// Obtener el título del arreglo JSON recibido
$titulo = $data['titulo'];

// Guardar el título en la base de datos

// Conectar a la base de datos
$host = 'localhost';
$db = 'login';
$user = 'root';
$pass = '';

// Intentar conectar a la base de datos
$conn = new mysqli($host, $user, $pass, $db);

// Verificar si hay errores en la conexión
if ($conn->connect_error) {
    echo json_encode(array('success' => false, 'message' => 'Error de conexión a la base de datos: ' . $conn->connect_error));
    exit;
}

// Insertar el título en la tabla `encuestas`
$sql = "INSERT INTO encuestas (titulo) VALUES (?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $titulo);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo json_encode(array('success' => true, 'message' => 'Título insertado correctamente.'));
} else {
    echo json_encode(array('success' => false, 'message' => 'No se pudo insertar el título.'));
}

$stmt->close();
$conn->close();
