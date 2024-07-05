
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!----======== CSS ======== -->
    <link rel="stylesheet" href="css/style3.css">
    <link rel="stylesheet" href="css/estilos123.css"> 
    <!----===== Iconscout CSS ===== -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <style>
        @font-face {
            font-family: 'Montserrat';
            src: url('fonts/Montserrat/static/Montserrat-Regular.ttf') format('truetype');
            /* Agrega las otras variaciones de la fuente si las tienes, como Bold, Italic, etc. */
        }

        body {
            font-family: 'Montserrat', sans-serif;
        }
    </style>
    <title>Contestar Encuesta</title> 
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
                <li><a href="index.php">
                    <i class="uil uil-estate"></i>
                    <span class="link-name">Inicio</span>
                </a></li>
                <li><a href="administrar_encuesta.php">
                    <i class="uil uil-chart"></i>
                    <span class="link-name">Encuestas</span>
                </a></li>
                
            </ul>
                <li class="mode">
                    
                <div class="mode-toggle">
                </div>
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
                    <span class="text">Servicios</span>
                </div>
                <div class="boxes" id="serviciosContainer">
                    
                </div>
                </div>
            </div>
            <button id="salirBtn"onclick="confirmarEliminarServicios()">SALIR</button>
    </section>
    
    <script src="js/script.js"></script>
    <script src="js/salir.js"></script>
    <script src="js/verServicio.js"></script>
    
</body>
</html>