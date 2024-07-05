<?php
// Conectar a la base de datos
$conn = new mysqli("localhost", "id22332073_root", "Diego&23", "id22332073_login");

if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Conexión fallida: ' . $conn->connect_error]));
}

// Obtener los datos del POST
$id_encuesta = intval($_POST['id_encuesta']);
$respuestas = $_POST['respuestas'];
$comentario = $_POST['comentario']; 

// Obtener el máximo contesto_id para la encuesta actual
$sql = "SELECT MAX(contesto_id) AS max_contesto_id FROM respuestas WHERE encuesta_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id_encuesta);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$max_contesto_id = $row['max_contesto_id'] ? $row['max_contesto_id'] : 0;

// Calcular el nuevo contesto_id
$new_contesto_id = $max_contesto_id + 1;
$stmt->close();

// Insertar un nuevo registro en la tabla contesto
$sql = "INSERT INTO contesto (encuesta_id) VALUES (?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id_encuesta);

if (!$stmt->execute()) {
    echo json_encode(['success' => false, 'message' => 'Error al insertar en contesto: ' . $stmt->error]);
    $stmt->close();
    $conn->close();
    exit;
}

$stmt->close();

// Insertar respuestas en la base de datos
$stmt = $conn->prepare("INSERT INTO respuestas (pregunta_id, respuesta, comentario, encuesta_id, contesto_id) VALUES (?, ?, ?, ?, ?)");
foreach ($respuestas as $respuesta) {
    $pregunta_id = intval($respuesta['pregunta_id']);
    $valor_respuesta = intval($respuesta['respuesta']); 

    $stmt->bind_param('iisii', $pregunta_id, $valor_respuesta, $comentario, $id_encuesta, $new_contesto_id);

    if (!$stmt->execute()) {
        echo json_encode(['success' => false, 'message' => 'Error al guardar las respuestas: ' . $stmt->error]);
        $stmt->close();
        $conn->close();
        exit;
    }
}

// Respuesta de éxito
echo json_encode(['success' => true]);
$stmt->close();
$conn->close();
?>
