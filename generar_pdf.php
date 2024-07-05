<?php
require('generar_reporte.php');
require('conexion.php');
require('grafica.php');
require('fpdf/fpdf.php'); // Asegúrate de que la ruta es correcta

if (isset($_POST['encuesta_id'])) {
    $encuesta_id = $_POST['encuesta_id'];

    // Consulta a la base de datos para obtener los datos necesarios para el PDF
    $query = "SELECT puntuacion FROM respuestas_prueba WHERE encuesta_id = $encuesta_id";
    $result = $conexion->query($query);

    if ($result->num_rows > 0) {
        $puntuaciones = [];
        while ($row = $result->fetch_assoc()) {
            $puntuaciones[] = $row['puntuacion'];
        }

        // Crear instancia de FPDF
        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(40, 10, 'Reporte de Encuesta');

        // Agregar datos estadísticos al PDF
        $pdf->Ln();
        $pdf->Cell(0, 10, 'Número de respuestas: ' . count($puntuaciones));
        // Puedes agregar más datos según sea necesario

        // Generar gráfico de barras y agregarlo al PDF
        $grafico_barras = generarGraficoBarras($puntuaciones);
        $pdf->Ln();
        $pdf->Cell(0, 10, 'Gráfico de Barras:');
        $pdf->Image('@' . base64_decode($grafico_barras), 10, $pdf->GetY() + 10, 100, 50);

        // Generar gráfico de pastel y agregarlo al PDF
        // $grafico_pastel = generarGraficoPastel($puntuaciones);
        // $pdf->Ln();
        // $pdf->Cell(0, 10, 'Gráfico de Pastel:');
        // $pdf->Image('@' . base64_decode($grafico_pastel), 10, $pdf->GetY() + 10, 100, 50);

        // Guardar o mostrar el PDF
        $pdf->Output('I', 'Reporte_Encuesta.pdf');
    } else {
        echo "<script>
        Swal.fire({
            icon: 'info',
            title: 'Información',
            text: 'No hay respuestas disponibles para esta encuesta.',
            confirmButtonText: 'OK'
        }).then(function() {
            window.location.href = 'reportes.php';
        });
        </script>";
    }
} else {
    echo 'ID de encuesta no especificado.';
}
?>