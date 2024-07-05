
<link rel="stylesheet" href="css/sweetalert2.min.css">
<script src="js/sweetalert2.all.min.js"></script>
<?php
// Conectar a la base de datos (asegúrate de tener esta conexión establecida)
include 'conexion.php';

// Verificar si se ha proporcionado un token en la URL
if (!empty($_GET['token'])) {
    $token = $_GET['token'];

    // Buscar el usuario por el token en la base de datos
    $sql_select = $conexion->query("SELECT * FROM usuario WHERE token = '$token'");

    if ($sql_select->num_rows > 0) {
        // Usuario encontrado, actualiza el estado a "verificado"
        $sql_update = $conexion->query("UPDATE usuario SET estado = 'verificado' WHERE token = '$token'");

        if ($sql_update) {
            ?>
            <body>
            <script>
                Swal.fire({
                    title: "¡Cuenta verificada!",
                    html: "Tu cuenta ha sido verificada con éxito.",
                    icon: "success",
                    timer: 2000,
                    timerProgressBar: true,
                    didOpen: () => {
                        Swal.showLoading();
                    },
                    willClose: () => {
                        window.location.href = 'login.php'; // Redirige a login.php al cerrar la alerta
                    }
                });
            </script>
            </body>
           
            <?php
        } else {
            ?>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error...',
                    text: 'Hubo un error al verificar la cuenta.',
                });
            </script>
            <?php
        }
    } else {
        ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Token inválido',
                text: 'Token no válido o cuenta ya verificada.',
            });
        </script>
        <?php
    }
} else {
    ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Token no proporcionado',
            text: 'Token no proporcionado en la URL.',
        });
    </script>
    <?php
}
?>
