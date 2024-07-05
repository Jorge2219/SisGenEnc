<?php
    session_start();
        $varsesion = $_SESSION['usuario'];
     if ($varsesion == null || $varsesion == '') {
        header("Location: login.php");
    exit();
     
     }
?>
<!DOCTYPE html>
<!--=== Coding by CodingLab | www.codinglabweb.com === -->
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!----======== CSS ======== -->
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" href="css/style3.css">
    <link rel="stylesheet" href="css/estilos123.css">
    <link rel="stylesheet" href="css/adptables.css"> 
    <!----===== Iconscout CSS ===== -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <link rel="stylesheet" href="css/sweetalert2.min.css">
    <script src="js/sweetalert2.all.min.js"></script>

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
            <img src="img/IPN.png" alt="">
        </div>

        <div class="dash-content">
            <div class="overview">
                <div class="title">
                    <i class="uil uil-user"></i>
                    <span class="text">Usuarios</span>
                </div>
                <?php
$servername = "localhost";
$username = "id22332073_root";
$password = "Diego&23";
$dbname = "id22332073_login";

$conexion = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conexion->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Consulta para obtener todos los usuarios
$sql = "SELECT * FROM usuario";
$result = $conexion->query($sql);

// Mostrar la lista de usuarios en una tabla con estilos externos y botones


// ...
// ...
if ($result->num_rows > 0) {
    echo "<link rel='stylesheet' type='text/css' href='estilos.css'>";
    echo "<h2>Lista de Usuarios</h2>";
    echo "<table>";
    echo "<tr><th>Usuario</th><th>Nombre</th><th>Apellidos</th><th>Área</th><th>Acciones</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["usuario"] . "</td>";
        echo "<td>" . $row["nombre"] . "</td>";
        echo "<td>" . $row["apellidos"] . "</td>";
        echo "<td>" . $row["Area"] . "</td>";
        echo "<td>
                <a href='#' onclick='editarUsuario(" . $row["id"] . ")'>Editar</a>  
                <a href='#' onclick='confirmarEliminacion(" . $row["id"] . ")'>Eliminar</a>
              </td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<script>
            console.log('No se encontraron usuarios');
            Swal.fire({
                title: 'Información',
                text: 'No hay usuarios disponibles.',
                icon: 'warning',
                confirmButtonText: 'OK'
            }).then((result) => {
                // Puedes redireccionar o hacer cualquier otra cosa después de cerrar el SweetAlert
                window.location.href = 'index.php';
            });
          </script>";
}
// ...

// ...

// Cerrar la conexión
$conexion->close();

?>

<a class="font-italic isai5" href="index.php">Regresar</a>

<script src="js/script.js"></script>
<script>
    function confirmarEliminacion(id) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: 'Esta acción eliminará al usuario. ¿Deseas continuar?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#4f1212',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Si el usuario confirma, redirige a eliminar_usuario.php con el parámetro id
                window.location.href = 'eliminar_usuario.php?id=' + id;
            }
        });
    }
</script>
<script>
    function editarUsuario(id) {
        // Realizar una solicitud AJAX para obtener la información del usuario
        fetch('editar_usuario.php?id=' + id)
    .then(response => {
        if (!response.ok) {
            throw new Error(`Error en la solicitud AJAX: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            Swal.fire('Usuario editado con éxito', '', 'success');
        } else {
            // Si el servidor responde con éxito: false, muestra un mensaje de error específico
            Swal.fire('Error al editar el usuario', data.message, 'error');
        }
    })
    .catch(error => {
        // Manejar el error general en la solicitud AJAX
        console.error(error);
        Swal.fire('Error', 'Hubo un problema al editar el usuario', 'error');
    });

    }

    function mostrarFormularioEdicion(usuario) {
        // Mostrar el formulario de edición con los datos actuales del usuario
        Swal.fire({
            title: 'Editar Usuario',
            html: `
                <input id="swal-nuevo-nombre" class="swal2-input" placeholder="Nuevo nombre del usuario" value="${usuario.nombre}">
                <input id="swal-nuevo-apellidos" class="swal2-input" placeholder="Nuevos apellidos del usuario" value="${usuario.apellidos}">
                <input id="swal-nueva-area" class="swal2-input" placeholder="Nueva área del usuario" value="${usuario.area}">
            `,
            showCancelButton: true,
            confirmButtonText: 'Guardar',
            cancelButtonText: 'Cancelar',
            showLoaderOnConfirm: true,
            preConfirm: () => {
                // Lógica para guardar los cambios, por ejemplo, mediante AJAX
                const nuevoNombre = document.getElementById('swal-nuevo-nombre').value;
                const nuevosApellidos = document.getElementById('swal-nuevo-apellidos').value;
                const nuevaArea = document.getElementById('swal-nueva-area').value;

                // Realizar una solicitud AJAX para guardar los cambios en el usuario
                return fetch('guardar_cambios_usuario.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        id: usuario.id, // Asegúrate de tener la propiedad "id" en el objeto $usuario
                        nuevoNombre: nuevoNombre,
                        nuevosApellidos: nuevosApellidos,
                        nuevaArea: nuevaArea
                    }),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Usuario editado con éxito', '', 'success');
                    } else {
                        Swal.fire('Error al editar el usuario', '', 'error');
                    }
                })
                .catch(error => {
                    Swal.fire('Error', 'Hubo un problema al guardar los cambios del usuario', 'error');
                });
            }
        });
    }
</script>


<script>
    window.history.forward();
</script>

</body>
</html>

