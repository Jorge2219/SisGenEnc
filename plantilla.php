<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Encuesta</title>
    <!-- Incluye la hoja de estilos de Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Agrega la fuente de Google Fonts para Montserrat -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap">
    <link rel="stylesheet" href="css/encuesta.css">
    <style>
        /* Regla de estilo personalizada para cambiar el color del thead */
        .custom-thead {
            background-color: #4f1212;
            color: white; /* Cambia el color del texto si es necesario */
        }

        /* Regla de estilo para cambiar la fuente a Montserrat */
        body, h1, th, td {
            font-family: 'Montserrat', sans-serif;
        }

        /* Estilo para los inputs en línea */
        .inline-input {
            width: 50px;
            display: inline-block;
            margin: 0 5px;
        }

        /* Estilo para el contenedor de opciones */
        .option-container {
            display: flex;
            align-items: center;
        }
        #descripcion .inline-input:first-of-type {
            display: none;
        }

        /* Estilo para el campo de periodo */
        #periodoInput {
            width: 100%;
            max-width: 300px; /* Ajusta el ancho máximo según sea necesario */
            margin: 0 auto; /* Centra el campo horizontalmente */
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <div class="row mb-4">
            <div class="col-md-4 text-center">
                <img src="img/LogoIPN.png" alt="Logo IPN" class="img-fluid" width="60">
            </div>
            <div class="col-md-4 d-flex justify-content-center align-items-center">
                <h2 class="text-center display-4" style="font-size: 24px;">Instituto Politécnico Nacional</h2>
            </div>
            <div class="col-md-4 text-center">
                <img src="img/LogoUPIIZ.png" alt="Logo UPIIZ" class="img-fluid" width="100">
            </div>
        </div>

        <!-- Campo para ingresar el periodo de la encuesta -->
        <div class="row mb-4">
            <div class="col text-center">
                <input type="text" id="periodoInput" name="periodo" class="form-control" placeholder="Ingrese el periodo (ej. 24-1)">
            </div>
        </div>

        <div class="row mb-4">
            <div class="col text-center">
                <input type="text" id="tituloInput" name="titulo" class="form-control title" placeholder="Ingrese el título">
            </div>
        </div>
        
        <h1 id="titulo" class="text-center mb-4">Encuesta de satisfacción a estudiantes</h1>

        <!-- Tabla adicional -->
        <div class="row">
            <!-- Columna izquierda -->
            <div class="col-md-6">
                <table class="table">
                    <tbody>
                        <tr>
                            <th scope="row">Unidad:</th>
                            <td>Unidad Profesional Interdisciplinaria de Ingenieria campus Zacatecas</td>
                        </tr>
                        <tr>
                            <th scope="row">Trámite o Servicio:</th>
                            <td>
                            <?php
                                $servicio = isset($_GET['servicio']) ? $_GET['servicio'] : 'N/A';
                                echo htmlspecialchars(urldecode($servicio)); // Asegura que los caracteres especiales se muestren correctamente
                            ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- Columna derecha -->
            <div class="col-md-6">
                <table class="table">
                    <tbody>
                        <tr>
                            <th scope="row">Folio No.:</th>
                            <td>001</td>
                        </tr>
                        <tr>
                            <th scope="row">Fecha:</th>
                            <td id="campoFecha"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- Espacio para descripción -->
        <div class="row mb-4">
            <div class="col text-center">
                <p id="descripcion" class="form-control" style="border: none; box-shadow: none;">
                    donde 1 <input type="text" class="inline-input" placeholder="1"> es 
                    y <input type="text" class="inline-input" placeholder="3">
                </p>
            </div>
        </div>
        <!-- Tabla principal -->
        <table id="miTabla" class="table">
            <thead class="custom-thead"> <!-- Utiliza la clase personalizada para el thead -->
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Pregunta</th>
                    <th scope="col">1</th>
                    <th scope="col">2</th>
                    <th scope="col">3</th>
                    <th scope="col"></th> <!-- Celda para el botón de eliminar -->
                </tr>
            </thead>
            <tbody>
                <!-- Botón para agregar nuevas filas -->
                <tr>
                    <td colspan="6">
                        <button id="agregarFila" class="btn btn-primary">AGREGAR</button>
                    </td>
                </tr>
            </tbody>
        </table>
        <!-- Caja de comentarios -->
        <div class="jumbotron">
            <h3>Comentarios</h3>
            <textarea class="form-control" rows="4" placeholder="Escribe tus comentarios aquí"></textarea>
        </div>
    </div>
    <!-- Incluye el script de JavaScript -->
    <script src="js/CrearNuevaEncuesta.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Incluye SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</body>
</html>
