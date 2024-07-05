<link rel="stylesheet" href="css/sweetalert2.min.css">
    <script src="js/sweetalert2.all.min.js"></script>
<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "login";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Verificar si se ha pasado un ID válido por el parámetro GET
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_usuario = $_GET['id'];

    // Consulta para eliminar el usuario
    $sql = "DELETE FROM usuario WHERE id = $id_usuario";

    if ($conn->query($sql) === TRUE) {
        ?>
        <body>
            <script>
            Swal.fire(
                    'Huélum',
                    'Usuario Eliminado!',
                    'success'
                ).then(function() {
                    // Redirigir a la página deseada
                    window.location.href = 'usua.php';
                });
        </script>
        </body>
        
        <?php
    } else {
        echo "Error al eliminar usuario: " . $conn->error;
    }
} else {
    echo "ID de usuario no válido";
}

// Cerrar la conexión
$conn->close();
?>
