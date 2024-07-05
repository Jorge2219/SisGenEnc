<?php
ob_start();  // Iniciar el almacenamiento en búfer de salida
session_start();

if (!empty($_POST["btningresar"])) {
    if (empty($_POST["usuario"]) && empty($_POST["password"])) {
        ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error...',
                text: 'Los campos están vacíos'
            });
        </script>
        <?php
    } elseif (empty($_POST["password"])) {
        echo '<div class="alert alert-danger">Ingrese usuario y contraseña</div>';
    } else {
        $usuario = $_POST["usuario"];
        $clave = $_POST["password"];
        
        // Preparar la consulta SQL
        $sql = $conexion->prepare("SELECT * FROM usuario WHERE usuario = ?");
        $sql->bind_param("s", $usuario);
        $sql->execute();
        $result = $sql->get_result();

        if ($datos = $result->fetch_object()) {
            // Verificar si la columna `clave` existe y está recuperada correctamente
            if (isset($datos->clave)) {
                // Verificar el estado de la cuenta
                if ($datos->estado == 'verificado') {
                    // Comparar la contraseña directamente
                    if ($clave === $datos->clave) {
                        // La cuenta está verificada y la contraseña es correcta, permitir el acceso
                        $_SESSION['usuario'] = $usuario;
                        $_SESSION['usuario_id'] = $datos->id;
                        $_SESSION['area'] = $datos->Area;
                        $Area = strtolower($datos->Area);

                        if ($Area == "admin" || $Area == "administrador") {
                            header("location: index.php");
                        } else {
                            header("location: index_JD.php");
                        }
                        exit;
                    } else {
                        // Contraseña incorrecta
                        ?>
                        <script>
                            Swal.fire({
                                icon: 'error',
                                title: 'Error...',
                                text: 'Contraseña incorrecta',
                                confirmButtonText: 'ACEPTAR'
                            });
                        </script>
                        <?php
                    }
                } else {
                    // La cuenta no está verificada
                    ?>
                    <script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Error...',
                            text: 'La cuenta no está verificada. Verifica tu cuenta antes de iniciar sesión.',
                            confirmButtonText: 'ACEPTAR'
                        });
                    </script>
                    <?php
                }
            } else {
                // La columna `clave` no fue recuperada correctamente
                echo '<div class="alert alert-danger">Error: No se pudo recuperar la contraseña. Verifique la columna `clave` en la base de datos.</div>';
            }
        } else {
            // Datos incorrectos
            ?>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error...',
                    text: 'Los datos son incorrectos',
                    footer: '<a href="registro.php">¿Desea registrarse?</a>',
                    confirmButtonText: 'ACEPTAR'
                });
            </script>
            <?php
        }
    }
    ob_end_flush();
}
?>
<script>
    setTimeout(() => {
        window.history.replaceState(null, null, window.location.pathname);
    }, 0);
</script>
