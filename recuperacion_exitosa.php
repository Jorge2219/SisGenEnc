
<link rel="stylesheet" href="css/sweetalert2.min.css">
<script src="js/sweetalert2.all.min.js"></script>
<?php
include 'conexion.php';

if (!empty($_POST["btnRecuperar"])) {
    $correo = $_POST["correo"];
    $sql_select = $conexion->query("SELECT * FROM usuario WHERE usuario = '$correo'");

    if ($sql_select->num_rows > 0) {
        $token_recuperacion = md5(uniqid(rand(), true));
        $sql_update_token = $conexion->query("UPDATE usuario SET token_recuperacion = '$token_recuperacion' WHERE usuario = '$correo'");

        if ($sql_update_token) {
            $asunto = "Recuperación de Contraseña";
            $mensaje = "Haz clic en el siguiente enlace para recuperar tu contraseña: http://192.168.10.17/login/recuperar_contraseña.php?token=$token_recuperacion";
            mail($correo, $asunto, $mensaje);

            ?>
            <body>
            <script>
                Swal.fire({
                    title: "¡Bien!",
                    html: "Se han enviado las instrucciones para recuperar la contraseña al correo electrónico",
                    icon: "success",
                    timer: 7000,
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
           
            <?php        } else {
            echo '<div class="alert alert-danger">Hubo un error al generar el enlace de recuperación de contraseña.</div>';
        }
    } else {
        echo '<div class="alert alert-danger">El correo electrónico no está registrado.</div>';
    }
}
?>
