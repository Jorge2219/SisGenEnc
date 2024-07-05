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

// Verificar si se recibieron datos JSON
$data = json_decode(file_get_contents('php://input'), true);

if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(array('success' => false, 'message' => 'Error en el JSON recibido: ' . json_last_error_msg()));
    exit;
}

// Obtener los datos del arreglo JSON recibido
$unidadAcademica = $data['unidad_academica'];
$fecha = $data['fecha'];
$descripcion = $data['descripcion'];
$preguntas = $data['preguntas'];
$idServicio = isset($data['id_servicio']) ? $data['id_servicio'] : null;
$tituloEncuesta = isset($data['titulo']) ? $data['titulo'] : 'Encuesta de satisfacción a estudiantes'; // Obtener el título de la encuesta
$periodo = isset($data['periodo']) ? $data['periodo'] : '2024-1'; // Obtener el periodo dinámicamente

// Comprobar y depurar el valor de id_servicio y periodo
if ($idServicio === null) {
    echo json_encode(array('success' => false, 'message' => 'id_servicio no proporcionado.'));
    exit;
}

// Formatear la fecha
$fecha = date('Y-m-d', strtotime($fecha));

// Obtener el último número de folio guardado en la base de datos para el id_servicio y periodo
$sql = "SELECT MAX(num_folio) AS max_folio FROM encuestas WHERE id_servicio = ? AND periodo = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $idServicio, $periodo);
$stmt->execute();
$result = $stmt->get_result();

if ($result) {
    $row = $result->fetch_assoc();
    $numFolio = $row['max_folio'] !== null ? $row['max_folio'] + 1 : 1; // Iniciar en 1 si no hay registros
} else {
    $numFolio = 1; // Empezar desde 1 si no hay registros
}

$stmt->close();

// Iniciar una transacción para asegurar la integridad de los datos
$conn->begin_transaction();

try {
    // Insertar la nueva encuesta en la tabla 'encuestas'
    $sqlEncuesta = "INSERT INTO encuestas (unidad_academica, num_folio, fecha, descripcion, id_servicio, titulo, periodo) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmtEncuesta = $conn->prepare($sqlEncuesta);
    $stmtEncuesta->bind_param("sssssss", $unidadAcademica, $numFolio, $fecha, $descripcion, $idServicio, $tituloEncuesta, $periodo);
    $stmtEncuesta->execute();

    if ($stmtEncuesta->affected_rows > 0) {
        // Obtener el ID de la encuesta recién insertada
        $encuestasId = $stmtEncuesta->insert_id;

        // Insertar las preguntas en la tabla 'preguntas'
        $sqlPregunta = "INSERT INTO preguntas (encuestas_id, pregunta, opciones) VALUES (?, ?, ?)";
        $stmtPregunta = $conn->prepare($sqlPregunta);

        foreach ($preguntas as $pregunta) {
            $textoPregunta = $pregunta['pregunta'];
            $numOpciones = count($pregunta['opciones']); // Contar el número de opciones

            $stmtPregunta->bind_param("isi", $encuestasId, $textoPregunta, $numOpciones);
            $stmtPregunta->execute();
        }

        // Confirmar la transacción
        $conn->commit();

        echo json_encode(array('success' => true, 'message' => 'Encuesta y preguntas insertadas correctamente.'));
    } else {
        echo json_encode(array('success' => false, 'message' => 'No se pudo insertar la encuesta.'));
    }

    $stmtEncuesta->close();
    $stmtPregunta->close();
} catch (Exception $e) {
    // Revertir la transacción en caso de error
    $conn->rollback();
    
    echo json_encode(array('success' => false, 'message' => 'Error al insertar la encuesta y preguntas: ' . $e->getMessage()));
}

// Cerrar la conexión a la base de datos
$conn->close();
?>
