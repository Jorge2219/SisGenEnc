<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';
$sql = false; // Inicializar la variable $sql

if (!empty($_POST["btnregistrar"])) {
    if (empty($_POST["usuario"]) or empty($_POST["password"])) {
        echo '<div class="alert alert-danger">Por favor llene los campos</div>';
    } else {
        $usuario = $_POST["usuario"];
        $clave = $_POST["password"];
        $nombre = $_POST["nombre"];
        $apellidos = $_POST["apellidos"];
        $area = $_POST["area"];

        // Verificar si el correo electrónico tiene el dominio "@ipn.mx" o "@gmail.com"
        if (strpos($usuario, '@ipn.mx') !== false || strpos($usuario, '@gmail.com') !== false) {
            // Generar tokens únicos
            $token = md5(uniqid(rand(), true));
            $token_recuperacion = md5(uniqid(rand(), true));

            // Guardar usuario con estado no verificado y tokens en la base de datos
            $sql_insert = $conexion->query("INSERT INTO usuario (usuario, clave, nombre, apellidos, area, estado, token, token_recuperacion) VALUES ('$usuario','$clave', '$nombre', '$apellidos', '$area', 'no verificado', '$token', '$token_recuperacion')");

            if ($sql_insert) {
                // Crear una instancia de PHPMailer
                $mail = new PHPMailer(true); // True activa excepciones

                try {
                    // Configurar el servidor SMTP
                   $mail->isSMTP();
                   $mail->Host = 'smtp.gmail.com';
                   $mail->SMTPAuth = true;
                   $mail->Username = 'sisgencupiiz@gmail.com';
                   $mail->Password = 'nvav hgnf acjh poyn'; // Asegúrate de usar una contraseña de aplicación
                   $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                   $mail->Port = 587;


                    // Configurar remitente y destinatario
                    $mail->setFrom('sisgencupiiz@gmail.com', 'Sistema Generador de Encuestas UPIIZ'); // Nombre opcional
                    $mail->addAddress($usuario); // Correo del destinatario

                    // Configurar contenido del correo
                    $mail->isHTML(true);
                    $mail->Subject = 'Verificacion de cuenta';
                    $mail->Body    = "Hola $nombre, haz clic en el siguiente enlace para verificar tu cuenta: <a href='https://sisgencupiiz.000webhostapp.com/verificar.php?token=$token'>Verificar cuenta</a>";
    
                    // Enviar el correo
                    $mail->send();

                    // Muestra mensaje de éxito
                    echo "<script>
                            Swal.fire(
                                'Huélum',
                                'Usuario registrado! Se ha enviado un correo de verificación.',
                                'success'
                            ).then(function () {
                                // Redirigir a la página deseada
                                window.location.href = 'login.php';
                            });
                          </script>";
                } catch (Exception $e) {
                    // Captura el error de PHPMailer
                    echo "<script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Error...',
                                text: 'Hubo un error al enviar el correo de verificación'
                            });
                          </script>";
                }
            } else {
                // Muestra mensaje de error al insertar en la base de datos
                echo '<div class="alert alert-danger">Hubo un error al registrar el usuario</div>';
            }
        } else {
            // Muestra mensaje de error de dominio de correo electrónico
            echo '<div class="alert alert-danger">El correo electrónico debe ser de dominio @ipn.mx o @gmail.com</div>';
        }
    }
}
?>
<script>
    setTimeout(() => {
        window.history.replaceState(null, null, window.location.pathname)
    }, 0);
</script>
