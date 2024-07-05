<?php
session_start();
$varsesion = $_SESSION['usuario'];
if ($varsesion == null || $varsesion == '') {
    header("Location: login.php");
    exit();
}

include 'conexion.php'; // Verificar y incluir el archivo de conexión

$query = "SELECT id, titulo FROM encuestas";
$result = $conexion->query($query);
?>
<!DOCTYPE html>
<html lang="es">
     <style>
        /* Estilos para los botones */
        button {
            padding: 10px 20px;
            margin: 5px;
            border: none;
            cursor: pointer;
            background-color: #4CAF50; /* Color de fondo */
            color: white; /* Color del texto */
             border-radius: 20px;
            transition: background-color 0.3s ease;
            width: 150px; /* Ancho fijo para todos los botones */
            box-sizing: border-box; /* Incluir padding y border en el ancho */
            text-align: center; /* Alinear texto al centro */
        }

         button:hover {
            opacity: 0.9; /* Reducción de la opacidad al pasar el mouse */
            filter: brightness(110%); /* Aclarar el color de fondo */
        }
         /* Ajuste del ancho de la columna Acciones */
        .acciones-column {
    width: 200px; /* Ancho máximo deseado */
    min-width: 150px; /* Ancho mínimo ajustado */
    overflow: hidden; /* Ocultar contenido que desborde */
}

           /* Estilos específicos para cada tipo de botón */
        .btn-reporte {
            background-color: #DC3545; /* rojo */
        }

        .btn-excel {
            background-color: #28A745; /* verde */
        }

        .btn-word {
            background-color: #174EA6; /* Azul */
        }

        /* Estilo para el contenedor de los botones */
        .button-container {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }
    </style>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/style3.css">
    <link rel="stylesheet" href="css/estilos123.css">
    <link rel="stylesheet" href="css/adptables.css">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <link rel="stylesheet" href="css/sweetalert2.min.css">
    <script src="js/sweetalert2.all.min.js"></script>
    <title>Reportes</title>
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
                    <span class="link-name">Servicios</span>
                </a></li>
                <li><a href="usua.php">
                    <i class="uil uil-user"></i>
                    <span class="link-name">Usuarios</span>
                </a></li>
                <li><a href="#">
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
            <img src="img/IPN.png" alt="">
        </div>
        <div class="dash-content">
            <div class="overview">
                <div class="title">
                    <i class="uil uil-user"></i>
                    <span class="text">Reportes</span>
                </div>
                <?php
                if ($result->num_rows > 0) {
                    echo "<h2>Lista de Encuestas</h2>";
                    echo "<table>";
                    echo "<tr><th>ID</th><th>Titulo</th><th class='acciones-column'>Acciones</th></tr>";
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["id"] . "</td>";
                        echo "<td>" . $row["titulo"] . "</td>";
                        echo "<td class='acciones-column'>
                                <form action='generar_reporte.php' method='GET' style='display: inline-block;'>
                                    <input type='hidden' name='encuesta_id' value='" . $row["id"] . "'>
                                    <button class='btn-reporte' type='submit'>Generar Pdf</button>
                                </form>
                                <form action='generar_excel.php' method='GET' style='display: inline-block;'>
                                    <input type='hidden' name='encuesta_id' value='" . $row["id"] . "'>
                                    <button class='btn-excel' type='submit'>Generar Excel</button>
                                </form>
                                <form action='generar_word.php' method='GET' style='display: inline-block;'>
                                    <input type='hidden' name='encuesta_id' value='" . $row["id"] . "'>
                                    <button class='btn-word' type='submit'>Generar Word</button>
                                </form>
                              </td>"; 
                        echo "</tr>";
                    }
                    echo "</table>";
                } else {
                    echo "<script>
                            Swal.fire({
                                title: 'Información',
                                text: 'No hay encuestas disponibles.',
                                icon: 'warning',
                                confirmButtonText: 'OK'
                            }).then((result) => {
                                window.location.href = 'index.php';
                            });
                          </script>";
                }
                ?>
                <a class="font-italic isai5" href="index.php">Regresar</a>
            </div>
        </div>
    </section>
    <script src="js/script.js"></script>
</body>
</html>
