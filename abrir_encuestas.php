<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!----======== CSS ======== -->
    <link rel="stylesheet" href="css/style3.css">
    <link rel="stylesheet" href="css/estilos123.css"> 
    <link rel="stylesheet" href="css/encuesta.css"> 

    <!----===== Iconscout CSS ===== -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">

    <style>
        @font-face {
            font-family: 'Montserrat';
            src: url('fonts/Montserrat/static/Montserrat-Regular.ttf') format('truetype');
        }

        body {
            font-family: 'Montserrat', sans-serif;
        }
    </style>

    <title>Nueva encuesta</title> 
</head>
<body>
<nav>
    <div class="logo-name">
        <div class="logo-image">
            <img src="img/candado.png" style="width: 50px; height: auto;" alt="">
        </div>

        <span class="logo_name">SisGenEnc</span>

        <div class="logo-image">
            <img src="img/upiiz.png" alt="">
        </div>
    </div>

    <div class="menu-items">
        <ul class="nav-links">
            <li><a href="index_usuario.php">
                <i class="uil uil-estate"></i>
                <span class="link-name">Inicio</span>
            </a></li>
        </ul>
        <li class="mode">
            <div class="mode-toggle"></div>
        </li>
        </ul>
    </div>
</nav>

<section class="dashboard">
    <div class="top">
        <i class="uil uil-bars sidebar-toggle"></i>

        <div class="search-box">
            <i class="uil uil-search"></i>
            <input type="text" placeholder="Buscar">
        </div>

        <img src="img/IPN.png" alt="">
    </div>

    <div class="dash-content">
        <div class="overview">
            <div class="title">
                <i class="uil uil-estate"></i>
                <span class="text">Encuestas disponibles</span>
            </div>
            <div class="boxes" id="serviciosContainer"></div>
        </div>

        <table id="miTabla" class="table">
            <thead class="custom-thead">
                <tr>
                    <th scope="col">Encuesta</th>
                    <th scope="col">Fecha</th>
                    <th scope="col">Contestar Encuesta</th> <!-- Columna para el botón Ver -->
                </tr>
            </thead>
            <tbody>
                <!-- Filas de encuestas se generarán dinámicamente aquí -->
            </tbody>
        </table>
    </div>
</section>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="js/script.js"></script>
<script src="js/contestar_encuesta.js"></script>

</body>
</html>
