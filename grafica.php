<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Gráficas</title>
    <!-- Incluir Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Incluir el archivo de estilos CSS -->
    <link rel="stylesheet" href="styles0.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Reporte de Gráficas</h1>
            <p>Análisis de Frecuencia de Categorías</p>
        </header>

        <section class="chart-container">
            <?php
            function generarGraficoBarras($data) {
                $chart_data = json_encode(array_count_values($data));
                return "
                    <canvas id='barChart'></canvas>
                    <script>
                        var ctx = document.getElementById('barChart').getContext('2d');
                        var chart = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: Object.keys($chart_data),
                                datasets: [{
                                    label: 'Frecuencia',
                                    data: Object.values($chart_data),
                                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                    borderColor: 'rgba(75, 192, 192, 1)',
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                scales: {
                                    y: {
                                        beginAtZero: true
                                    }
                                }
                            }
                        });
                    </script>
                ";
            }

            function generarGraficoPastel($data) {
                $chart_data = json_encode(array_count_values($data));
                return "
                    <canvas id='pieChart'></canvas>
                    <script>
                        var ctx = document.getElementById('pieChart').getContext('2d');
                        var chart = new Chart(ctx, {
                            type: 'pie',
                            data: {
                                labels: Object.keys($chart_data),
                                datasets: [{
                                    label: 'Frecuencia',
                                    data: Object.values($chart_data),
                                    backgroundColor: [
                                        'rgba(75, 192, 192, 0.2)',
                                        'rgba(255, 99, 132, 0.2)',
                                        'rgba(255, 206, 86, 0.2)',
                                        'rgba(54, 162, 235, 0.2)'
                                    ],
                                    borderColor: [
                                        'rgba(75, 192, 192, 1)',
                                        'rgba(255, 99, 132, 1)',
                                        'rgba(255, 206, 86, 1)',
                                        'rgba(54, 162, 235, 1)'
                                    ],
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false
                            }
                        });
                    </script>
                ";
            }

            // Ejemplo de datos
            $data = ['A', 'B', 'A', 'C', 'B', 'A'];

            // Generar y mostrar gráficos
            echo generarGraficoBarras($data);
            echo generarGraficoPastel($data);
            ?>
        </section>
    </div>
</body>
</html>