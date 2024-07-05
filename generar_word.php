<?php
session_start();
require('conexion.php');
require 'vendor/autoload.php'; // Incluir el autoloader de Composer

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

// Obtener ID de la encuesta desde la URL
$encuesta_id = isset($_GET['encuesta_id']) ? intval($_GET['encuesta_id']) : 0;

// Consulta para obtener los detalles de la encuesta y del servicio
$query = "SELECT e.titulo AS encuesta, s.nombre AS servicio 
          FROM encuestas e
          JOIN servicios s ON e.id_servicio = s.id 
          WHERE e.id = $encuesta_id";
$result = $conexion->query($query);

if ($result->num_rows == 0) {
    die("Encuesta no encontrada.");
}

$detalles_encuesta = $result->fetch_assoc();

// Consulta para obtener las preguntas de la encuesta
$query_preguntas = "SELECT id, pregunta FROM preguntas WHERE encuestas_id = $encuesta_id";
$result_preguntas = $conexion->query($query_preguntas);

// Crear nuevo documento Word
$phpWord = new PhpWord();
$section = $phpWord->addSection();

// Encabezado
$section->addImage('img/LogoIPN.png', array('width' => 50, 'height' => 50, 'alignment' => 'left'));
$section->addImage('img/LogoUPIIZ.png', array('width' => 50, 'height' => 50, 'alignment' => 'right'));
$section->addText('Instituto Politécnico Nacional', array('bold' => true, 'size' => 12), array('alignment' => 'center'));
$section->addText('Unidad Profesional Interdisciplinaria de Ingeniería campus Zacatecas', array('bold' => true, 'size' => 12), array('alignment' => 'center'));
$section->addText('Título del servicio: ' . $detalles_encuesta['servicio'], array('bold' => true, 'size' => 12), array('alignment' => 'center'));
$section->addText('Título de la encuesta: ' . $detalles_encuesta['encuesta'], array('bold' => true, 'size' => 12), array('alignment' => 'center'));
$section->addTextBreak(2);

// Definición de la función calcularEstadisticas
function calcularEstadisticas($puntuaciones) {
    $n = count($puntuaciones);
    if ($n == 0) {
        return [
            'media' => 0,
            'mediana' => 0,
            'desviacion' => 0,
        ];
    }
    $media = array_sum($puntuaciones) / $n;
    sort($puntuaciones);
    $mediana = ($n % 2 == 0) ? ($puntuaciones[$n / 2 - 1] + $puntuaciones[$n / 2]) / 2 : $puntuaciones[floor($n / 2)];
    $desviacion = sqrt(array_sum(array_map(function ($x) use ($media) {
        return pow($x - $media, 2);
    }, $puntuaciones)) / $n);

    return [
        'media' => $media,
        'mediana' => $mediana,
        'desviacion' => $desviacion,
    ];
}

// Iterar sobre cada pregunta
while ($pregunta = $result_preguntas->fetch_assoc()) {
    $pregunta_id = $pregunta['id'];
    $texto_pregunta = $pregunta['pregunta'];

    // Obtener puntuaciones de la pregunta actual
    $query_respuestas = "SELECT respuesta FROM respuestas WHERE encuesta_id = $encuesta_id AND pregunta_id = $pregunta_id";
    $result_respuestas = $conexion->query($query_respuestas);

    $puntuaciones = [];
    while ($respuesta = $result_respuestas->fetch_assoc()) {
        $puntuaciones[] = $respuesta['respuesta'];
    }

    // Calcular estadísticas descriptivas
    $estadisticas = calcularEstadisticas($puntuaciones);

    // Contar las frecuencias de cada puntuación
    $frecuencias = array_count_values($puntuaciones);
    ksort($frecuencias);

    // Generar datos para la gráfica
    $labels = [];
    $data = [];
    foreach ($frecuencias as $puntuacion => $conteo) {
        $labels[] = $puntuacion;
        $data[] = $conteo;
    }

    // Verificar si hay datos para evitar errores
    if (!empty($data)) {
        $max_value = max($data);
        $suggested_max = $max_value + 1;
    } else {
        $max_value = 0;
        $suggested_max = 1;
    }

    // Generar URL de la gráfica de barras con colores para todas las barras
    $chart_url_bar = "https://quickchart.io/chart?c=" . urlencode(json_encode([
        "type" => "bar",
        "data" => [
            "labels" => $labels,
            "datasets" => [[
                "label" => "Frecuencia",
                "data" => $data,
                "backgroundColor" => "rgba(75, 192, 192, 0.2)",
                "borderColor" => "rgba(75, 192, 192, 1)",
                "borderWidth" => 1
            ]]
        ],
        "options" => [
            "scales" => [
                "y" => ["beginAtZero" => true, "suggestedMax" => $suggested_max]
            ]
        ]
    ]));

    // Generar URL de la gráfica de pastel
    $chart_url_pie = "https://quickchart.io/chart?c=" . urlencode(json_encode([
        "type" => "pie",
        "data" => [
            "labels" => $labels,
            "datasets" => [[
                "label" => "Frecuencia",
                "data" => $data,
                "backgroundColor" => ["#FF6384", "#36A2EB", "#FFCE56", "#4BC0C0", "#9966FF", "#FF9F40"],
                "hoverBackgroundColor" => ["#FF6384", "#36A2EB", "#FFCE56", "#4BC0C0", "#9966FF", "#FF9F40"]
            ]]
        ]
    ]));

    // Agregar pregunta y tabla de frecuencias al documento Word
    $section->addText($texto_pregunta, array('bold' => true, 'size' => 12));
    $section->addText('Frecuencias:', array('bold' => true, 'size' => 12));

    // Agregar imagen de gráfica de barras
    $section->addImage($chart_url_bar, array('width' => 400, 'height' => 300));

    // Agregar imagen de gráfica de pastel
    $section->addImage($chart_url_pie, array('width' => 400, 'height' => 300));
}

// Guardar el documento
$file_name = 'Encuesta_' . $encuesta_id . '.docx';
header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
header('Content-Disposition: attachment; filename="' . $file_name . '"');
header('Cache-Control: max-age=0');

$objWriter = IOFactory::createWriter($phpWord, 'Word2007');
$objWriter->save('php://output');
exit();
?>