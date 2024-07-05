<?php
// Verificar si el parámetro 'id' está presente en la URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id_encuesta = $_GET['id'];
} else {
    die("ID de encuesta no proporcionado.");
}

// Conectar a la base de datos y obtener los detalles de la encuesta
$conn = new mysqli("localhost", "id22332073_root", "Diego&23", "id22332073_login");

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener los detalles de la encuesta incluyendo el número de folio
$sql_encuesta = "SELECT *, LPAD(num_folio, 3, '0') AS 'No. Folio', periodo FROM encuestas WHERE id = $id_encuesta";
$result_encuesta = $conn->query($sql_encuesta);

if ($result_encuesta->num_rows > 0) {
    $encuesta = $result_encuesta->fetch_assoc();
} else {
    die("Encuesta no encontrada.");
}

// Obtener el nombre del servicio
$id_servicio = $encuesta['id_servicio'];
$sql_servicio = "SELECT nombre FROM servicios WHERE id = $id_servicio";
$result_servicio = $conn->query($sql_servicio);

if ($result_servicio->num_rows > 0) {
    $servicio = $result_servicio->fetch_assoc()['nombre'];
} else {
    $servicio = "Servicio no encontrado";
}

// Obtener las preguntas de la encuesta incluyendo el número de opciones
$sql_preguntas = "SELECT * FROM preguntas WHERE encuestas_id = $id_encuesta";
$result_preguntas = $conn->query($sql_preguntas);
$preguntas = [];
$num_opciones = 0;

if ($result_preguntas->num_rows > 0) {
    while ($row = $result_preguntas->fetch_assoc()) {
        $preguntas[] = $row;
        $num_opciones = max($num_opciones, $row['opciones']);
    }
} else {
    die("No se encontraron preguntas para esta encuesta.");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Encuesta</title>
    <!-- Incluye la hoja de estilos de Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Agrega la fuente de Google Fonts para Montserrat -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap">
    <!-- Ajusta la ruta de encuesta.css según la estructura de carpetas de tu proyecto -->
    <link rel="stylesheet" href="css/encuesta.css">
    <style>
        .custom-thead {
            background-color: #4f1212;
            color: white;
        }
        body, h1, th, td {
            font-family: 'Montserrat', sans-serif;
        }
        .inline-input {
            width: 50px;
            display: inline-block;
            margin: 0 5px;
        }
        .option-container {
            display: flex;
            align-items: center;
        }
        .custom-radio {
            position: relative;
            appearance: none;
            width: 20px;
            height: 20px;
            background-color: #fff;
            border: 2px solid #4f1212;
            border-radius: 50%;
            cursor: pointer;
            outline: none;
            transition: background 0.3s;
        }
        .custom-radio:checked {
            background-color: #4f1212;
            border: 2px solid #4f1212;
        }
        .custom-radio:checked::after {
            content: '';
            position: absolute;
            top: 5px;
            left: 5px;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: #fff;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <!-- Botón de Regresar -->
        <div class="row mb-2">
            <div class="col-md-1">
                <a href="javascript:history.back()" class="btn btn-secondary">Regresar</a>
            </div>
        </div>

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

        <div class="row mb-4">
            <div class="col text-center">
                <h1 class="text-center mb-4"><?php echo $encuesta['titulo']; ?></h1>
                <p>
    
                    <br>
                    <p>En una escala donde 1 es "completamente en desacuerdo" y <span id="num-opciones"><?php echo $num_opciones; ?></span> es "completamente de acuerdo". ¿como calificarías los siguientes puntos?</p>
                </p>
            </div>
        </div>

        <!-- Tabla de datos adicionales -->
        <div class="row">
            <!-- Columna izquierda -->
            <div class="col-md-6">
                <table class="table">
                    <tbody>
                        <tr>
                            <th scope="row">Unidad:</th>
                            <td>Unidad Profesional Interdisciplinaria de Ingenieria campus Zacatecas (UPIIZ)</td>
                        </tr>
                        <tr>
                            <th scope="row">Trámite o Servicio:</th>
                            <td><?php echo htmlspecialchars($servicio); ?></td>
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
                            <td style="color: red;"><?php echo $encuesta['No. Folio']; ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Fecha:</th>
                            <td><?php echo date("d/m/Y", strtotime($encuesta['fecha'])); ?></td>
                        </tr>
                        <tr>
                            <th scope="row">Periodo:</th>
                            <td><?php echo htmlspecialchars($encuesta['periodo']); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tabla principal -->
        <table id="miTabla" class="table">
            <thead class="custom-thead">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Pregunta</th>
                    <?php for ($i = 1; $i <= max(array_column($preguntas, 'opciones')); $i++) { ?>
                        <th scope="col"><?php echo $i; ?></th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($preguntas as $index => $pregunta) { ?>
                    <tr>
                        <th scope="row"><?php echo $index + 1; ?></th>
                        <td><?php echo $pregunta['pregunta']; ?></td>
                        <?php for ($i = 1; $i <= $pregunta['opciones']; $i++) { ?>
                            <td><input type="radio" name="respuesta_<?php echo $pregunta['id']; ?>" value="<?php echo $i; ?>" class="custom-radio"></td>
                        <?php } ?>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <!-- Caja de comentarios -->
        <div class="jumbotron">
            <h3>Comentarios</h3>
            <textarea class="form-control" rows="4" placeholder="Escribe tus comentarios aquí"></textarea>
        </div>
    </div>

    <!-- Incluye el script de JavaScript -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
</body>
</html>
