document.addEventListener('DOMContentLoaded', function() {
    const salirBtn = document.getElementById('salirBtn');

    if (salirBtn) {
        salirBtn.addEventListener('click', function() {
            confirmarSalir();
        });
    }
});

function confirmarSalir() {
    // Puedes agregar lógica adicional aquí si es necesario
    // Por ejemplo, mostrar un mensaje de confirmación antes de salir

    // Redirige al usuario a la página de inicio de sesión al hacer clic en "SALIR"
    window.location.href = 'login.php';
}
