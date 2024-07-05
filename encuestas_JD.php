
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>


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
            /* Agrega las otras variaciones de la fuente si las tienes, como Bold, Italic, etc. */
        }
 
.btn-excel {
    background-color: #6c757d; /* Color gris */
    color: white;
    border: none;
    padding: 10px 20px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 14px;
    margin: 10px 2px;
    cursor: pointer;
    border-radius: 20px;
    transition: background-color 0.3s ease; /* Transición suave del color de fondo */
}

.btn-excel:hover {
    background-color: #515b62; /* Nuevo color gris más oscuro al pasar el cursor */
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
                <li><a href="index_JD.php">
                    <i class="uil uil-estate"></i>
                    <span class="link-name">Inicio</span>
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
                    <span class="text">Mis encuestas</span>
                </div>
                <div class="boxes" id="serviciosContainer">
                    
                </div>
                </div>
                <table id="miTabla" class="table">
    <thead class="custom-thead">
        <tr>
            <th scope="col">Encuesta</th>
            <th scope="col">Fecha</th>
            <th scope="col">Acciones</th>
            <!-- Columna para el botón Ver -->
        </tr>
    </thead>
    <tbody>
        <!-- Filas de encuestas se generarán dinámicamente aquí -->
    </tbody>

<button id="CrearBtn">CREAR NUEVA</button>
</table>
            </div>
            
            
    </section>
    
    <script src="js/script.js"></script>
    <script src="js/CrearNuevaEncuesta.js"></script>
<script src="js/obtener_encuestas.js"></script>    
    
</body>
</html>