<?php
    session_start();
        $varsesion = $_SESSION['usuario'];
        $nombreUsuario = ""; // Variable para almacenar el nombre del usuario

        if ($varsesion != null && $varsesion != '') {
            // Obtener el nombre del usuario desde la base de datos
            include("conexion.php"); // Asegúrate de incluir el archivo de conexión
    
            // Consulta para obtener el nombre del usuario
            $consultaUsuario = $conexion->query("SELECT nombre, apellidos FROM usuario WHERE usuario = '$varsesion'");
    
            if ($filaUsuario = $consultaUsuario->fetch_assoc()) {
                $nombreUsuario = $filaUsuario['nombre'] . ' ' . $filaUsuario['apellidos'];
            } else {
                // Manejar el caso en que no se encuentre el usuario en la base de datos
                // Puedes redirigir a una página de error o realizar otras acciones necesarias
                header("Location: error.php");
                exit();
            }
    
            // Cerrar la conexión a la base de datos
            $conexion->close();
        } else {
            // Si la sesión no está iniciada, redirigir al usuario a la página de inicio de sesión
            header("Location: login.php");
            exit();
        }
?>
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
    <script src="https://cdn.jsdelivr.net/g/filesaver.js"></script>
    <!----===== Librería spectrum ===== -->
    <link rel="stylesheet" type="text/css" href="css/spectrum.css">
    <script type="text/javascript" src="js/spectrum.js"></script>
    

   <!----===== font-Awesome ===== -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

   <link rel="stylesheet" href="css/sweetalert2.min.css">
   <script src="js/sweetalert2.all.min.js"></script>
    <title>Inicio</title> 
</head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!----======== CSS ======== -->
    <link rel="stylesheet" href="css/style3.css">
    <link rel="stylesheet" href="css/estilos123.css">
    <link rel="stylesheet" href="css/nombre.css">
    <!----===== Iconscout CSS ===== -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <script src="https://cdn.jsdelivr.net/g/filesaver.js"></script>
    <title>Inicio</title> 
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
                    <span class="link-name">Servicios</span>
                </a></li>
                <li><a href="encuestas.php">
                    <i class="uil uil-chart"></i>
                    <span class="link-name">Encuestas</span>
                </a></li>
                <li><a href="reportes.php">
                    <i class="uil uil-comments"></i>
                    <span class="link-name">Reportes</span>
                </a></li>
            </ul>
            
            <ul class="logout-mode">
                <li>
                    <a href="cerrar_sesion.php">
                        <i class="uil uil-signout"></i>
                        <span class="link-name">Salir</span>
                    </a>
                </li>
            </ul>
                <li class="mode">
                    <a href="#">
                        <i class="uil uil-moon"></i>
                    <span class="link-name">Modo oscuro</span>
                </a>

                <div class="mode-toggle">
                  <span class="switch"></span>
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
            <div class="user-container" onclick="toggleUserInfo()">
        <img src="img/IPN.png" alt="">
        <span class="user-info" id="userInfo"><?php echo $nombreUsuario; ?></span>
    </div>

    <script>
    function toggleUserInfo() {
        var userInfo = document.getElementById('userInfo');
        userInfo.style.display = (userInfo.style.display === 'block' ? 'none' : 'block');
    }
    document.querySelector('.user-container').addEventListener('click', function() {
    this.classList.toggle('active');
});
</script>

        </div>

        <div class="dash-content">
            <div class="overview">
                <div class="title">
                    <i class="uil uil-estate"></i>
                    <span class="text">Servicios</span>
                </div>
                
                
                <div class="boxes" id="serviciosContainer">
                    
                    <div class="box box3" onclick="crearNuevoBox()">
                        <span class="text"></span>
                        <span class="number">+</span>
                    </div>
                </div>
            </div>

        <button id="eliminarServiciosBtn"onclick="confirmarEliminarServicios()">Eliminar Servicio</button>
        <button id="editarServicioBtn" onclick="mostrarFormularioEditar()">Editar Servicio</button>
        
    </section>

<!-- Agrega la referencia a FontAwesome -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
    <script src="js/script.js"></script>
    <script src="js/Crearservicios.js"> </script>
    <script>
        window.history.forward();
    </script>
    
    
    
</body>
</html>