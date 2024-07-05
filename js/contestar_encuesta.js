$(document).ready(function() {
    var idServicio = obtenerIdServicio();

    if (idServicio === 0) {
        console.error("No se proporcionó un id válido.");
        return;
    }

    $.ajax({
        type: "GET",
        url: "obtener_encuestas.php",
        data: { id_servicio: idServicio },
        success: function(response) {
            console.log("Respuesta del servidor:", response); // Registrar la respuesta en la consola
            try {
                // Verificar que la respuesta tenga éxito
                if (response.success) {
                    var encuestas = response.encuestas;

                    // Limpiar el cuerpo de la tabla antes de agregar nuevas filas
                    $('#miTabla tbody').empty();

                    // Iterar sobre las encuestas y crear filas en la tabla
                    encuestas.forEach(function(encuesta) {
                        var fila = `
                            <tr>
                                <td>${encuesta.titulo}</td>
                                <td>${encuesta.fecha}</td>
                                <td><button class="btn-contestar" data-id="${encuesta.id}">Contestar</button></td>
                            </tr>
                        `;
                        $('#miTabla tbody').append(fila);
                    });

                    // Agregar evento de clic para los botones de contestar encuesta
                    $('.btn-contestar').click(function() {
                        var encuestaId = $(this).data('id');
                        Swal.fire({
                            title: 'Redirigiendo...',
                            icon: 'success',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.href = `responder.php?id=${encuestaId}`;
                        });
                    });
                } else {
                    console.error("Error del servidor:", response.message);
                }
            } catch (error) {
                console.error("Error al analizar la respuesta JSON:", error);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error("Error en la solicitud AJAX:", textStatus, errorThrown);
        }
    });

    function obtenerIdServicio() {
        // Obtén el ID del servicio de la URL
        const urlParams = new URLSearchParams(window.location.search);
        return parseInt(urlParams.get('id'), 10) || 0;
    }
});
