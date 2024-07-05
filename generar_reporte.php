<?php
require('conexion.php');
require('TCPDF-main/tcpdf.php');

// Deshabilitar la salida de errores y advertencias
error_reporting(0);

// Obtener ID de la encuesta desde la URL
$encuesta_id = $_GET['encuesta_id'];

// Consulta para obtener los detalles de la encuesta y del servicio
$query = "SELECT e.titulo AS encuesta, s.nombre AS servicio 
          FROM encuestas e
          JOIN servicios s ON e.id_servicio = s.id 
          WHERE e.id = $encuesta_id";
$result = $conexion->query($query);
$detalles_encuesta = $result->fetch_assoc();

// Consulta para obtener las preguntas de la encuesta
// Consulta para obtener las preguntas de la encuesta
$query_preguntas = "SELECT id, pregunta FROM preguntas WHERE encuestas_id = $encuesta_id";
$result_preguntas = $conexion->query($query_preguntas);


// Función para calcular estadísticas descriptivas
function calcularEstadisticas($puntuaciones) {
    $n = count($puntuaciones);
    if ($n == 0) {
        return [
            'media' => 0,
            'mediana' => 0,
            'desv_estandar' => 0,
            'varianza' => 0,
            'min' => 0,
            'max' => 0
        ];
    }

    $media = array_sum($puntuaciones) / $n;
    sort($puntuaciones);
    $mediana = $puntuaciones[(int)($n / 2)];
    $desv_estandar = sqrt(array_sum(array_map(function($x) use ($media) {
        return pow($x - $media, 2);
    }, $puntuaciones)) / $n);
    $varianza = pow($desv_estandar, 2);
    $min = min($puntuaciones);
    $max = max($puntuaciones);

    return [
        'media' => $media,
        'mediana' => $mediana,
        'desv_estandar' => $desv_estandar,
        'varianza' => $varianza,
        'min' => $min,
        'max' => $max
    ];
}

// Crear nuevo PDF
$pdf = new TCPDF();
$pdf->AddPage();

// Encabezado con imágenes y texto
$pdf->SetFont('helvetica', 'B', 12);
$pdf->Image('C:/xampp/htdocs/Login/img/LogoIPN.png', 15, 10, 20); // Ajustar tamaño del logo
$pdf->Image('C:/xampp/htdocs/Login/img/LogoUPIIZ.png', 175, 10, 20); // Ajustar tamaño del logo
$pdf->Cell(0, 10, 'Instituto Politécnico Nacional', 0, 1, 'C');
$pdf->Cell(0, 10, 'Unidad Profesional Interdisciplinaria de Ingeniería campus Zacatecas', 0, 1, 'C');
$pdf->Cell(0, 10, 'Título del servicio: ' . $detalles_encuesta['servicio'], 0, 1, 'C');
$pdf->Cell(0, 10, 'Título de la encuesta: ' . $detalles_encuesta['encuesta'], 0, 1, 'C');
$pdf->Ln(20);

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
            "datasets" => [
                [
                    "label" => "Puntuaciones",
                    "data" => $data,
                    "backgroundColor" => array_fill(0, count($data), 'rgba(75, 192, 192, 0.6)'),
                    "borderColor" => array_fill(0, count($data), 'rgba(75, 192, 192, 1)'),
                    "borderWidth" => 1
                ]
            ]
        ],
        "options" => [
            "scales" => [
                "yAxes" => [
                    [
                        "ticks" => [
                            "beginAtZero" => true,
                            "suggestedMax" => $suggested_max
                        ]
                    ]
                ],
                "xAxes" => [
                    [
                        "scaleLabel" => [
                            "display" => true,
                            "labelString" => "puntuacion"
                        ]
                    ]
                ]
            ]
        ]
    ]));

    // Generar URL de la gráfica de pastel
    $chart_url_pie = "https://quickchart.io/chart?c=" . urlencode(json_encode([
        "type" => "pie",
        "data" => [
            "labels" => $labels,
            "datasets" => [
                [
                    "label" => "Puntuaciones",
                    "data" => $data,
                    "backgroundColor" => [
                        'rgba(75, 192, 192, 0.6)',
                        'rgba(255, 99, 132, 0.6)',
                        'rgba(255, 206, 86, 0.6)',
                        'rgba(54, 162, 235, 0.6)',
                        'rgba(153, 102, 255, 0.6)',
                        'rgba(255, 159, 64, 0.6)',
                        'rgba(201, 203, 207, 0.6)',
                        'rgba(255, 99, 132, 0.6)',
                        'rgba(54, 162, 235, 0.6)',
                        'rgba(255, 206, 86, 0.6)',
                        'rgba(75, 192, 192, 0.6)',
                        'rgba(153, 102, 255, 0.6)',
                        'rgba(255, 159, 64, 0.6)'
                    ],
                    "borderColor" => [
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(201, 203, 207, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    "borderWidth" => 1
                ]
            ]
        ]
    ]));

    // Agregar pregunta y gráficas al PDF
    $pdf->SetFont('helvetica', 'B', 10);
    $pdf->Cell(0, 10, 'Pregunta: ' . $texto_pregunta, 0, 1);
    
    // Gráfica de barras
    $img_file = @file_get_contents($chart_url_bar);
    if ($img_file !== false) {
        $pdf->Image('@' . $img_file, '', '', 100, 100, 'PNG');
        $pdf->Ln(105); // Espacio adicional después de la gráfica
    } else {
        $pdf->Cell(0, 10, 'No se pudo generar la gráfica de barras.', 0, 1);
        $pdf->Ln(10); // Espacio adicional en caso de error
    }

    // Gráfica de pastel
    $img_file = @file_get_contents($chart_url_pie);
    if ($img_file !== false) {
        $pdf->Image('@' . $img_file, '', '', 100, 100, 'PNG');
        $pdf->Ln(105); // Espacio adicional después de la gráfica
    } else {
        $pdf->Cell(0, 10, 'No se pudo generar la gráfica de pastel.', 0, 1);
        $pdf->Ln(10); // Espacio adicional en caso de error
    }

    // Insertar estadísticas descriptivas
    $pdf->SetFont('helvetica', '', 10);
    $pdf->Cell(0, 10, 'Media: ' . $estadisticas['media'], 0, 1);
    $pdf->Cell(0, 10, 'Mediana: ' . $estadisticas['mediana'], 0, 1);
    $pdf->Cell(0, 10, 'Desviación Estándar: ' . $estadisticas['desv_estandar'], 0, 1);
    $pdf->Cell(0, 10, 'Varianza: ' . $estadisticas['varianza'], 0, 1);
    $pdf->Cell(0, 10, 'Min: ' . $estadisticas['min'], 0, 1);
    $pdf->Cell(0, 10, 'Max: ' . $estadisticas['max'], 0, 1);
    $pdf->Ln(10); // Espacio adicional después de las estadísticas
}

// Cerrar y enviar el PDF
$pdf->Output('reporte.pdf', 'I');
