function crearNuevoBox() {
    // Utilizamos SweetAlert2 para mostrar un formulario interactivo
    Swal.fire({
        title: 'Ingrese los datos del nuevo servicio',
        html:
        '<input id="swal-nombre" class="swal2-input" placeholder="Nombre del servicio" style="margin-bottom: 10px;">' +
            '<div style="display: flex; flex-direction: column; align-items: center;">' +
            '<div style="margin-bottom: 10px; text-align: center;">Selecciona un color:</div>' +
            '<div style="display: flex; align-items: center;">' +
            '<input type="color" id="swal-color-selector" class="swal2-input swal-color-selector" style="width: 80px; height: 30px;">' +
            '<div id="color-preview" style="width: 30px; height: 30px; margin-left: 10px; display: inline-block;"></div>'+
            '</div>' +
            '<div style="margin-top: 10px; text-align: center;">Selecciona un icono:</div>' +
            '<select id="swal-icono-selector" class="swal2-input" style="margin-top: 10px;">' +
                '<option value="uil-user">Persona</option>' +
                '<option value="uil-ambulance">Ambulancia</option>' +
                '<option value="uil-book-open">Libro</option>' +
                '<option value="uil-book-reader">Estudiante</option>' +
                '<!-- Agrega más opciones según sea necesario -->' +
            '</select>' +
            '</div>',
        focusConfirm: false,
        showCancelButton: true,
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#4CAF50',
        cancelButtonColor: '#4f1212',
        preConfirm: () => {
            const nombre = Swal.getPopup().querySelector('#swal-nombre').value;
            const colorSelector = Swal.getPopup().querySelector('#swal-color-selector');
            const colorSeleccionado = colorSelector.value;
            const iconoSelector = Swal.getPopup().querySelector('#swal-icono-selector');
            const iconoSeleccionado = iconoSelector.value;

            if (nombre && colorSeleccionado && iconoSeleccionado) {
                $.ajax({
                    type: 'POST',
                    url: 'guardar_servicio.php',
                    data: { nombre: nombre, color: colorSeleccionado, icono: iconoSeleccionado},
                    success: function (response) {
                        console.log(response);

                        if (response.indexOf('Servicio guardado en la base de datos') !== -1) {
                            crearYAgregarNuevoBox(nombre, colorSeleccionado, iconoSeleccionado);
                        } else {
                            console.error('Error al guardar en la base de datos:', response);
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.error('Error en la solicitud AJAX:', textStatus, errorThrown);
                    },
                });
            } else {
                Swal.showValidationMessage('Todos los campos son obligatorios');
            }
        },
        didOpen: () => {
            Swal.getPopup().style.width = '45%'; // Puedes ajustar el porcentaje según tus preferencias

            // Actualiza la vista previa del color al abrir la alerta
            const colorSelector = Swal.getPopup().querySelector('#swal-color-selector');
            const colorPreview = Swal.getPopup().querySelector('#color-preview');
        
            colorSelector.addEventListener('input', () => {
                colorPreview.style.backgroundColor = colorSelector.value;
            });
            colorSelector.style.margin = '0 auto';
        },
    }).then((result) => {
        if (result.isConfirmed) {
            location.reload();
        }
    });
}
        // Cargar servicios al cargar la página
        window.onload = function () {
            // solicitud AJAX para obtener los servicios almacenados
            $.ajax({
                type: "GET",
                url: "obtener_servicios.php", //  script PHP que obtiene los servicios desde la base de datos
                success: function(response) {
                    try {
                        var result = JSON.parse(response);
                        // Verifica si la respuesta contiene un error
                        if (result.error) {
                            console.error('Error del servidor:', result.error);
                        } else {
                            // Verifica que la respuesta sea un array antes de intentar iterar sobre ella
                            if (Array.isArray(result)) {
                                result.forEach(function(servicio) {
                                    // Tu lógica aquí para manejar cada servicio
                                });
                            } else {
                                console.error('La respuesta del servidor no es un array:', result);
                            }
                        }
                    } catch (error) {
                        console.error('Error al analizar la respuesta JSON:', error);
                    }
                    // Parsea la respuesta JSON
                    var servicios = JSON.parse(response);
                    // Itera sobre los servicios y crea los elementos en la página
                    servicios.forEach(function (servicio) {
                        var nuevoBox = document.createElement("div");
                        nuevoBox.className = "box";
                        nuevoBox.style.backgroundColor = servicio.color;
                        const colorTexto = esColorOscuro(servicio.color) ? '#ffffff' : '#000000';
                        const colorIcono = esColorOscuro(servicio.color) ? '#ffffff' : '#000000';
                        nuevoBox.innerHTML = `
                            <i class="uil ${servicio.icono}" style="color: ${colorIcono};"></i>
                            <span class="text" style="color: ${colorTexto};">${servicio.nombre}</span>
                            <span class="texto"></span>
                            <a href="encuestas.php?servicio=${encodeURIComponent(servicio.nombre)}&id=${encodeURIComponent(servicio.id)}" style="display: block; width: 100%; height: 100%; position: absolute; top: 0; left: 0;">
                                <button style="width: 100%; height: 100%; opacity: 0; border: none;" class="text"></button>
                            </a>
                        `;
                        function getBrightness(color) {
                            const rgb = parseInt(color.slice(1), 16);
                            const r = (rgb >> 16) & 0xff;
                            const g = (rgb >>  8) & 0xff;
                            const b = (rgb >>  0) & 0xff;
                            return 0.299 * r + 0.587 * g + 0.114 * b;
                        }
                        function esColorOscuro(color) {
                            const brillo = getBrightness(color);
                            return brillo < 128;
                        }
                        document.querySelector(".boxes").appendChild(nuevoBox);
                        nuevoBox.addEventListener('click', function() {
                            console.log("Clic en el box:", servicio.nombre);
                            window.location.href = "encuestas.php?servicio=" + encodeURIComponent(servicio.nombre) + "&id=" + encodeURIComponent(servicio.id);
                        });
                    });
                    

                },
                error: function(jqXHR, textStatus, errorThrown) {
                    // Maneja el error de la solicitud AJAX
                    console.error("Error en la solicitud AJAX:", textStatus, errorThrown);
                }
            });
        };
        
        function confirmarEliminarServicios() {
            // Realizar una solicitud AJAX para obtener los servicios almacenados
            $.ajax({
                type: "GET",
                url: "obtener_servicios.php", // Ajusta la ruta según tu estructura de directorios
                success: function(response) {
                    var servicios = JSON.parse(response);
        
                    // Construir opciones para el select del formulario
                    var optionsHtml = '';
                    servicios.forEach(function(servicio) {
                        optionsHtml += `<option value="${servicio.id}">${servicio.nombre}</option>`;
                    });
        
                    // Muestra una alerta de confirmación con SweetAlert2
                    Swal.fire({
                        title: 'Seleccione el servicio a eliminar',
                        html:
                            `<select id="swal-servicio-selector" class="swal2-input">${optionsHtml}</select>`,
                        showCancelButton: true,
                        confirmButtonText: 'Eliminar',
                        cancelButtonText: 'Cancelar',
                        confirmButtonColor: '#4f1212',
                        cancelButtonColor: '#4CAF50',
                    }).then((result) => {
                        // Si el usuario confirma, obtén el ID del servicio seleccionado y llama a la función eliminarServicio()
                        if (result.isConfirmed) {
                            var idServicioSeleccionado = $('#swal-servicio-selector').val();
                            eliminarServicio(idServicioSeleccionado);
                        }
                    });
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error("Error en la solicitud AJAX:", textStatus, errorThrown);
                }
            });
        }
        
        function eliminarServicio(idServicio) {
            // Realizar una solicitud AJAX para eliminar el servicio específico
            $.ajax({
                type: "POST",
                url: "eliminar_servicios.php",
                data: { idServicio: idServicio },
                success: function(response) {
                    // Maneja la respuesta del servidor
                    console.log(response);
        
                    if (response === "Servicio eliminado de la base de datos") {
                        // Si la eliminación fue exitosa, recarga la página para reflejar los cambios
                        location.reload();
                    } else {
                        // Si hubo un problema, muestra un mensaje de error
                        console.error("Error al eliminar el servicio:", response);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    // Maneja el error de la solicitud AJAX
                    console.error("Error en la solicitud AJAX:", textStatus, errorThrown);
                }
            });
        }
        // ...

function mostrarFormularioEditar() {
    // Realizar una solicitud AJAX para obtener los servicios almacenados
    $.ajax({
        type: "GET",
        url: "obtener_servicios.php",
        success: function(response) {
            var servicios = JSON.parse(response);

            // Construir opciones para el select del formulario
            var optionsHtml = '';
            servicios.forEach(function(servicio) {
                optionsHtml += `<option value="${servicio.id}">${servicio.nombre}</option>`;
            });

            // Muestra un selector de servicios con SweetAlert2
            Swal.fire({
                title: 'Seleccione el servicio a editar',
                html: `<select id="swal-servicio-selector" class="swal2-input">${optionsHtml}</select>`,
                showCancelButton: true,
                confirmButtonText: 'Seleccionar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#4CAF50',
                cancelButtonColor: '#4f1212',
            }).then((result) => {
                // Si el usuario confirma, obtén el ID del servicio seleccionado y muestra el formulario de edición
                if (result.isConfirmed) {
                    var idServicioSeleccionado = $('#swal-servicio-selector').val();
                    mostrarFormularioEdicion(idServicioSeleccionado);
                }
            });
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error("Error en la solicitud AJAX:", textStatus, errorThrown);
        }
    });
}

function mostrarFormularioEdicion(idServicio) {
    // Función para obtener los detalles del servicio
    function obtenerDetallesServicio() {
        return new Promise((resolve, reject) => {
            // Realizar una solicitud AJAX para obtener los detalles del servicio
            $.ajax({
                type: "GET",
                url: "obtener_servicios.php",
                data: { idServicio: idServicio },
                success: function(response) {
                    const servicio = JSON.parse(response);
                    resolve(servicio);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    reject(new Error("Error en la solicitud AJAX:" + textStatus));
                }
            });
        });
    }

    // Llamar a la función y manejar el resultado usando Promesas
    obtenerDetallesServicio()
        .then((servicio) => {
            // Ahora puedes acceder a la variable servicio aquí
            const nombreServicio = servicio && servicio.nombre ? servicio.nombre : '';

            Swal.fire({
                title: `Editar Servicio: ${nombreServicio}`,
                html:
                    '<input id="swal-nuevo-nombre" class="swal2-input" placeholder="Nuevo nombre del servicio" style="margin-bottom: 10px;" value="' + (nombreServicio || '') + '">' +
                    '<br>' +
                    '<div style="margin-bottom: 10px; text-align: center;">Selecciona el nuevo color:</div>'+
                    '<input type="color" id="swal-nuevo-color-selector" class="swal2-input swal-color-selector" style="width: 80px; height: 30px;' + (servicio.color || '') + '">' +
                    '<div id="color-preview" style="width: 30px; height: 30px; margin-left: 10px; display: inline-block; background-color: ' + (servicio.color || '') + ';"></div>' +
                    '<br>' +
                    '<div style="margin-bottom: 10px; text-align: center;">Selecciona el nuevo ícono:</div>'+
                    '<select id="swal-nuevo-icono-selector" class="swal2-input" style="margin-top: 10px;">' +
                        '<option value="uil-user">Persona</option>' +
                        '<option value="uil-ambulance">Ambulancia</option>' +
                        '<option value="uil-book-open">Libro</option>' +
                        '<option value="uil-book-reader">Estudiante</option>' +
                        '<!-- Agrega más opciones según sea necesario -->' +
                    '</select>',
                showCancelButton: true,
                confirmButtonText: 'Guardar cambios',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#4CAF50',
                cancelButtonColor: '#4f1212',
                preConfirm: () => {
                    const nuevoNombre = Swal.getPopup().querySelector('#swal-nuevo-nombre').value;
                    const nuevoColorSelector = Swal.getPopup().querySelector('#swal-nuevo-color-selector');
                    const nuevoColor = nuevoColorSelector.value;
                    const nuevoIconoSelector = Swal.getPopup().querySelector('#swal-nuevo-icono-selector');
                    const nuevoIcono = nuevoIconoSelector.value;

                    // Verificar que todos los campos estén completos
                    if (nuevoNombre && nuevoColor && nuevoIcono) {
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
                    } else {
                        Swal.showValidationMessage('Todos los campos son obligatorios');
                    }
                },
                didOpen: () => {
                    Swal.getPopup().style.width = '45%'; 

                    // Actualiza la vista previa del color al abrir la alerta
                    const nuevoColorSelector = Swal.getPopup().querySelector('#swal-nuevo-color-selector');
                    const colorPreview = Swal.getPopup().querySelector('#color-preview');
                
                    nuevoColorSelector.addEventListener('input', () => {
                        colorPreview.style.backgroundColor = nuevoColorSelector.value;
                    });
                    nuevoColorSelector.style.margin = '0 auto';
                },
            });
        })
        .catch((error) => {
            console.error(error.message);
        });
}    