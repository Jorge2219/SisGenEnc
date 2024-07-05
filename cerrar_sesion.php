<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

if (isset($_SESSION['usuario'])) {
    // Cierre de sesión
    session_unset();
    session_destroy();

    // Redirige a la página de inicio de sesión
    header("Location: login.php");
    exit();
} else {
    // Si la sesión no está iniciada, muestra un mensaje de error o realiza otras acciones necesarias
    echo "La sesión no está iniciada.";
}

// JavaScript para limpiar y deshabilitar el historial del navegador
?>
<script>
    history.pushState(null, null, location.href);
    window.addEventListener('popstate', function () {
        history.pushState(null, null, location.href);
    });
</script>
<?php
exit();
?>
