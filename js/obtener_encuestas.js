$(document).ready(function() {
    // Función para obtener encuestas y mostrarlas en la tabla
    function obtenerEncuestas() {
        var idServicio = obtenerParametroURL('id'); // Obtener el ID del servicio de la URL
        var periodoSeleccionado = $('#selectorPeriodo').val(); // Obtener el periodo seleccionado

        $.ajax({
            url: 'obtener_encuestas.php',
            method: 'GET',
            dataType: 'json',
            data: { id_servicio: idServicio, periodo: periodoSeleccionado }, // Pasar id_servicio y periodo como parámetros
            success: function(response) {
                if (response.success) {
                    var encuestas = response.encuestas;
                    var tbody = $('#miTabla tbody');
                    tbody.empty(); // Limpiar el contenido existente del tbody

                    encuestas.forEach(function(encuesta) {
                        var row = '<tr>' +
                            '<td>' + encuesta.titulo + '</td>' +
                            '<td>' + encuesta.periodo + '</td>' +
                            '<td>' +
                                '<button class="btn btn-danger btn-eliminar" onclick="eliminarEncuesta(' + encuesta.id + ')">Eliminar</button>' +
                                '<button class="btn btn-primary btn-ver custom-ver-btn ml-2" onclick="verEncuesta(' + encuesta.id + ')">Ver</button>' +
                                '<button class="btn btn-success btn-excel ml-2" onclick="generarExcel(' + encuesta.id + ')">Reporte</button>' +
                            '</td>' +
                        '</tr>';

                        tbody.append(row);
                    });
                    
                } else {
                    Swal.fire('Error', response.message, 'error');
                }
            },
            error: function(xhr, status, error) {
                Swal.fire('Error', 'No se pudo obtener las encuestas.', 'error');
            }
        });
    }

    // Llamar a la función para obtener encuestas al cargar la página
    obtenerEncuestas();

    // Hacer la función obtenerEncuestas accesible desde fuera
    window.obtenerEncuestas = obtenerEncuestas;
});


function eliminarEncuesta(id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "¡No podrás revertir esto!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminarla'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: 'eliminar_encuesta.php',
                method: 'POST',
                data: { id: id },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        Swal.fire('Eliminada', response.message, 'success');
                        // Eliminar la fila de la tabla
                        obtenerEncuestas();
                    } else {
                        Swal.fire('Error', response.message, 'error');
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire('Error', 'No se pudo eliminar la encuesta.', 'error');
                }
            });
        }
    });
    
}


// Nueva función para ver encuesta
function verEncuesta(id) {
    // Redirigir a una nueva página para ver la encuesta
    window.location.href = 'ver_encuesta.php?id=' + id;
}

// Nueva función para generar el reporte en Excel
function generarExcel(id) {
    // Redirigir a la página que genera el Excel
    window.location.href = 'generar_excel.php?encuesta_id=' + id;
}
