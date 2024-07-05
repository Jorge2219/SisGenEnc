<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

if (!isset($_GET['encuesta_id'])) {
    die('Error: encuesta_id no está definido.');
}

$encuesta_id = $_GET['encuesta_id'];

// Conexión a la base de datos
$mysqli = new mysqli("localhost", "id22332073_root", "Diego&23", "id22332073_login");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Obtener el título del servicio, el comentario y el periodo desde la base de datos
$queryEncuesta = "SELECT e.titulo, s.nombre AS servicio, e.periodo
                  FROM encuestas e
                  JOIN servicios s ON e.id_servicio = s.id 
                  WHERE e.id = ?";
$stmtEncuesta = $mysqli->prepare($queryEncuesta);
$stmtEncuesta->bind_param("i", $encuesta_id);
$stmtEncuesta->execute();
$resultEncuesta = $stmtEncuesta->get_result();
$encuesta = $resultEncuesta->fetch_assoc();
$tituloEncuesta = $encuesta ? $encuesta['titulo'] : 'Desconocida';
$nombreServicio = $encuesta ? $encuesta['servicio'] : 'Desconocido';
$periodoEncuesta = $encuesta ? $encuesta['periodo'] : 'Desconocido';

// Consulta SQL para obtener las preguntas y sus respuestas
$sqlPreguntas = "SELECT id, pregunta FROM preguntas WHERE encuestas_id = ? ORDER BY id";
$sqlRespuestas = "SELECT contesto_id, pregunta_id, respuesta FROM respuestas WHERE encuesta_id = ? ORDER BY contesto_id, pregunta_id";

$stmtPreguntas = $mysqli->prepare($sqlPreguntas);
$stmtPreguntas->bind_param("i", $encuesta_id);
$stmtPreguntas->execute();
$resultPreguntas = $stmtPreguntas->get_result();

$preguntas = [];
while ($row = $resultPreguntas->fetch_assoc()) {
    $preguntas[] = $row;
}

$stmtRespuestas = $mysqli->prepare($sqlRespuestas);
$stmtRespuestas->bind_param("i", $encuesta_id);
$stmtRespuestas->execute();
$resultRespuestas = $stmtRespuestas->get_result();

$respuestas = [];
while ($row = $resultRespuestas->fetch_assoc()) {
    $respuestas[$row['contesto_id']][$row['pregunta_id']] = $row['respuesta'];
}

// Obtener los comentarios únicos por contesto_id
$queryComentarios = "SELECT contesto_id, MAX(comentario) AS comentario FROM respuestas WHERE encuesta_id = ? GROUP BY contesto_id";
$stmtComentarios = $mysqli->prepare($queryComentarios);
$stmtComentarios->bind_param("i", $encuesta_id);
$stmtComentarios->execute();
$resultComentarios = $stmtComentarios->get_result();

$comentarios = [];
while ($rowComentario = $resultComentarios->fetch_assoc()) {
    $comentarios[$rowComentario['contesto_id']] = $rowComentario['comentario'];
}

$mysqli->close();

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Configuración de estilos
$boldStyle = [
    'font' => ['bold' => true],
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
];

$borderStyle = [
    'borders' => [
        'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => '000000']],
    ],
];

$fillPantone7420Style = [
    'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['argb' => 'FFAB2328']],
];

$fillPantone424Style = [
    'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['argb' => 'FF7C7F7E']],
];

$fillPantone468Style = [
    'fill' => ['fillType' => Fill::FILL_SOLID, 'color' => ['argb' => 'FFE0D7AF']],
];

$blackFontStyle = ['font' => ['color' => ['argb' => Color::COLOR_BLACK]]];

$whiteFontStyle = ['font' => ['color' => ['argb' => Color::COLOR_WHITE]]];

$centerAlignmentStyle = [
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
];

// Encabezados y configuraciones de la tabla
$sheet->mergeCells('C1:R1');
$sheet->mergeCells('C2:R2');
$sheet->mergeCells('C3:R3');
$sheet->mergeCells('C4:R4');
$sheet->mergeCells('C5:R5');
$sheet->setCellValue('C1', 'Instituto Politécnico Nacional');
$sheet->setCellValue('C2', 'Unidad Profesional Interdisciplinaria de Ingeniería campus Zacatecas');
$sheet->setCellValue('C3', 'Título del servicio: ' . $nombreServicio);
$sheet->setCellValue('C4', 'Título de la encuesta: ' . $tituloEncuesta);
$sheet->setCellValue('C5', 'Periodo: ' . $periodoEncuesta);

// Establecer alineación centrada para las celdas fusionadas
$centerStyle = [
    'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
    ],
];

$sheet->getStyle('C1:R1')->applyFromArray($centerStyle);
$sheet->getStyle('C2:R2')->applyFromArray($centerStyle);
$sheet->getStyle('C3:R3')->applyFromArray($centerStyle);
$sheet->getStyle('C4:R4')->applyFromArray($centerStyle);
$sheet->getStyle('C5:R5')->applyFromArray($centerStyle);

// Agregar logotipos
$logoIPN = new Drawing();
$logoIPN->setName('Logo IPN');
$logoIPN->setDescription('Logo IPN');
$logoIPN->setPath('img/LogoIPN.png');
$logoIPN->setHeight(90);
$logoIPN->setCoordinates('B1');
$logoIPN->setWorksheet($spreadsheet->getActiveSheet());

$logoUPIIZ = new Drawing();
$logoUPIIZ->setName('Logo UPIIZ');
$logoUPIIZ->setDescription('Logo UPIIZ');
$logoUPIIZ->setPath('img/LogoUPIIZ.png');
$logoUPIIZ->setHeight(90);
$logoUPIIZ->setCoordinates('R1');
$logoUPIIZ->setWorksheet($spreadsheet->getActiveSheet());

$sheet->setCellValue('B8', 'PREGUNTAS');
$sheet->getStyle('B8')->applyFromArray($boldStyle);
$sheet->getStyle('B8')->applyFromArray($fillPantone468Style);
$sheet->getStyle('B8')->applyFromArray($borderStyle);
$sheet->getStyle('B8')->applyFromArray($centerAlignmentStyle);

// Escribir encabezados de ID de respuesta (representan a las personas que respondieron)
$col = 'C';
$numRespuestas = array_keys($respuestas);
foreach ($numRespuestas as $num) {
    $sheet->setCellValue($col . '8', $num);
    $sheet->getStyle($col . '8')->applyFromArray($fillPantone7420Style);
    $sheet->getStyle($col . '8')->applyFromArray($whiteFontStyle);
    $sheet->getStyle($col . '8')->applyFromArray($centerAlignmentStyle);
    $col++;
}

// Escribir preguntas y respuestas en el archivo de Excel
$row = 9;
foreach ($preguntas as $pregunta) {
    $sheet->setCellValue('B' . $row, $pregunta['pregunta']);
    $sheet->getStyle('B' . $row)->applyFromArray($fillPantone7420Style);
    $sheet->getStyle('B' . $row)->applyFromArray($whiteFontStyle);
    $sheet->getStyle('B' . $row)->applyFromArray($centerAlignmentStyle);
    $col = 'C';
    foreach ($numRespuestas as $num) {
        if (isset($respuestas[$num][$pregunta['id']])) {
            $sheet->setCellValue($col . $row, $respuestas[$num][$pregunta['id']]);
            $sheet->getStyle($col . $row)->applyFromArray($blackFontStyle);
            $sheet->getStyle($col . $row)->applyFromArray($centerAlignmentStyle);
        }
        $col++;
    }
    $row++;
}

// Agregar comentarios de la encuesta en una fila separada
$sheet->setCellValue('B' . $row, 'Comentarios');
$sheet->getStyle('B' . $row)->applyFromArray($fillPantone424Style);
$sheet->getStyle('B' . $row)->applyFromArray($whiteFontStyle);
$sheet->getStyle('B' . $row)->applyFromArray($centerAlignmentStyle);
$col = 'C';
foreach ($numRespuestas as $num) {
    if (isset($comentarios[$num])) {
        $sheet->setCellValue($col . $row, $comentarios[$num]);
        $sheet->getStyle($col . $row)->applyFromArray($blackFontStyle);
        $sheet->getStyle($col . $row)->applyFromArray($centerAlignmentStyle);
    }
    $col++;
}

// Establecer tamaño de las columnas
$sheet->getColumnDimension('B')->setWidth(50);
$col = 'C';
foreach ($numRespuestas as $num) {
    $sheet->getColumnDimension($col)->setWidth(15);
    $col++;
}


// Obtener el período desde la base de datos
$periodoEncuesta = $encuesta ? $encuesta['periodo'] : 'Desconocido';

// Guardar el archivo y enviar al navegador
$filename = "reporte_encuesta_${encuesta_id}_periodo_${periodoEncuesta}.xlsx"; // Nombre del archivo con encuesta_id y periodo

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment;filename=\"$filename\"");
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit();


?>
