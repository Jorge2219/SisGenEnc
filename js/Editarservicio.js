function mostrarFormularioEditar(idServicio) {
    // Realizar una solicitud AJAX para obtener los detalles del servicio
    $.ajax({
        type: "GET",
        url: "obtener_servicio.php",  // Ajusta la ruta según tu estructura de directorios
        data: { idServicio: idServicio },
        success: function(response) {
            var servicio = JSON.parse(response);

            // Muestra un formulario de edición con los detalles del servicio
            Swal.fire({
                title: 'Editar Servicio',
                html:
                    `<input id="swal-nuevo-nombre" class="swal2-input" value="${servicio.nombre}" placeholder="Nuevo nombre del servicio">` +
                    `<input id="swal-nuevo-color" class="swal2-input" value="${servicio.color}" placeholder="Nuevo color del servicio">` +
                    `<input id="swal-nuevo-icono" class="swal2-input" value="${servicio.icono}" placeholder="Nuevo icono del servicio">`,
                showCancelButton: true,
                confirmButtonText: 'Guardar cambios',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#4CAF50',
                cancelButtonColor: '#4f1212',
                preConfirm: () => {
                    const nuevoNombre = Swal.getPopup().querySelector('#swal-nuevo-nombre').value;
                    const nuevoColor = Swal.getPopup().querySelector('#swal-nuevo-color').value;
                    const nuevoIcono = Swal.getPopup().querySelector('#swal-nuevo-icono').value;

                    // Realizar una solicitud AJAX para guardar los cambios en el servicio
                    $.ajax({
                        type: 'POST',
                        url: 'editar_servicio.php',
                        data: { idServicio: idServicio, nuevoNombre: nuevoNombre, nuevoColor: nuevoColor, nuevoIcono: nuevoIcono },
                        success: function (response) {
                            console.log(response);

                            if (response.indexOf('Servicio editado en la base de datos') !== -1) {
                                // Actualizar la página o hacer algo más después de editar el servicio
                                location.reload();
                            } else {
                                console.error('Error al editar el servicio:', response);
                            }
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            console.error('Error en la solicitud AJAX:', textStatus, errorThrown);
                        },
                    });
                },
                didOpen: () => {
                    Swal.getPopup().style.width = '45%'; // Puedes ajustar el porcentaje según tus preferencias
                },
            });
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error("Error en la solicitud AJAX:", textStatus, errorThrown);
        }
    });
}
