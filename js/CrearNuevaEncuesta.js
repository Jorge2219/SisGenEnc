// Asegúrate de que el DOM esté completamente cargado antes de ejecutar cualquier código JavaScript
document.addEventListener('DOMContentLoaded', function() {

    // Intenta obtener el botón con ID 'CrearBtn'
    var CrearBtn = document.getElementById('CrearBtn');

    // Verifica si se encontró el botón
    if (CrearBtn) {
        // Si se encontró, agrega un event listener para el evento click
        CrearBtn.addEventListener('click', function() {
            CrearNuevaEncuesta(); // Llama a la función CrearNuevaEncuesta cuando se haga clic en el botón
        });
    } else {
        // Si no se encontró el botón, muestra un mensaje de error en la consola
        console.error('No se encontró el botón con el ID CrearBtn.');
    }

});

function obtenerParametroURL(nombre) {
    nombre = nombre.replace(/[[]/, '\\[').replace(/[\]]/, '\\]');
    var regex = new RegExp('[\\?&]' + nombre + '=([^&#]*)');
    var resultados = regex.exec(location.search);
    return resultados === null ? '' : decodeURIComponent(resultados[1].replace(/\+/g, ' '));
}

function CrearNuevaEncuesta() {
    var servicio = obtenerParametroURL('servicio') || 'N/A';

    Swal.fire({
        title: 'Nueva Encuesta',
        html: `<iframe id="iframeEncuesta" width="100%" height="400px" src="https://sisgencupiiz.000webhostapp.com/plantilla/plantilla.php?servicio=${encodeURIComponent(servicio)}"></iframe>`, // Cambio de localhost a la URL remota
        showCancelButton: true,
        confirmButtonText: 'Crear',
        cancelButtonText: 'Cancelar',
        width: '90%',
        padding: '3rem',
        customClass: {
            confirmButton: 'custom-confirm-button',
            content: 'sweet-alert-iframe'
        },
        preConfirm: () => {
            try {
                const iframe = document.getElementById('iframeEncuesta').contentWindow.document;

                // Obtener el valor del título desde el iframe
                var tituloEncuesta = iframe.getElementById('tituloInput').value.trim();

                // Obtener el número de folio del iframe
                var thElements = iframe.querySelectorAll('th');
                var numeroFolioText = '';

                thElements.forEach(function(thElement) {
                    if (thElement.textContent.trim() === 'Folio No.:') {
                        var tdElement = thElement.nextElementSibling;
                        numeroFolioText = tdElement.textContent.trim();
                    }
                });

                if (!numeroFolioText) {
                    throw new Error('No se encontró el número de folio.');
                }

                var unidadAcademica = 'Unidad Profesional Interdisciplinaria de Ingeniería campus Zacatecas (UPIIZ)';
                var descripcion = iframe.querySelector('#descripcion').textContent.trim();
                var idServicio = obtenerParametroURL('id'); // Obtener el ID del servicio de la URL
                var fecha = obtenerFechaISO(iframe.querySelector('td#campoFecha').textContent.trim());

                console.log('Fecha obtenida del iframe:', fecha);

                var periodo = iframe.querySelector('#campoPeriodo').value.trim();

                if (!periodo) {
                    throw new Error('El periodo no puede estar vacío.');
                }

                var preguntas = [];
                var filas = iframe.querySelectorAll('#miTabla tbody tr');
                filas.forEach(function(fila, index) {
                    var textarea = fila.querySelector('textarea');
                    if (textarea) {
                        var pregunta = textarea.value.trim();
                        var opciones = [];
                        var radios = fila.querySelectorAll('input[type="radio"]');
                        radios.forEach(function(radio) {
                            opciones.push({
                                opcion: parseInt(radio.value),  // Convertir el valor a entero
                                seleccionado: radio.checked
                            });
                        });

                        preguntas.push({
                            pregunta: pregunta,
                            opciones: opciones
                        });
                    }
                });

                var datos = {
                    unidad_academica: unidadAcademica,
                    num_folio: numeroFolioText,
                    descripcion: descripcion,
                    id_servicio: idServicio,
                    fecha: fecha,
                    periodo: periodo, // Agregar el periodo a los datos a enviar
                    preguntas: preguntas,
                    titulo: tituloEncuesta // Agregar el título de la encuesta
                };

                console.log('Datos a enviar:', datos);

                return fetch('https://sisgencupiiz.000webhostapp.com/insertar_encuesta.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(datos)
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(response.statusText);
                    }
                    return response.json();
                })
                .then(data => {
                    if (!data.success) {
                        throw new Error(data.message || 'Error al insertar los datos');
                    }
                })
                .catch(error => {
                    Swal.showValidationMessage(`Request failed: ${error}`);
                });
            } catch (error) {
                Swal.showValidationMessage(`Error: ${error.message}`);
                console.error('Error during preConfirm:', error);
            }
        }
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire('¡Hecho!', 'Los datos se han guardado correctamente.', 'success')
                .then(() => {
                    obtenerEncuestas(); // Llamar a obtenerEncuestas después de que la encuesta se haya creado con éxito
                });
        }
    });
}

document.getElementById('tituloInput').addEventListener('input', function() {
    document.getElementById('titulo').textContent = this.value || 'Encuesta de satisfacción a estudiantes';
});

function obtenerNumeroOpciones() {
    var descripcionInput = document.querySelectorAll('.inline-input')[1];
    return parseInt(descripcionInput.value) || 3;
}

function actualizarEncabezadoTabla() {
    var numeroOpciones = obtenerNumeroOpciones();
    var thead = document.getElementById('miTabla').getElementsByTagName('thead')[0];
    var tr = thead.getElementsByTagName('tr')[0];
    tr.innerHTML = '';

    var thNumero = document.createElement('th');
    thNumero.scope = 'col';
    thNumero.textContent = '#';
    tr.appendChild(thNumero);

    var thPregunta = document.createElement('th');
    thPregunta.scope = 'col';
    thPregunta.textContent = 'Pregunta';
    tr.appendChild(thPregunta);

    for (var i = 1; i <= numeroOpciones; i++) {
        var thOpcion = document.createElement('th');
        thOpcion.scope = 'col';
        thOpcion.textContent = i.toString();
        tr.appendChild(thOpcion);
    }

    var thEliminar = document.createElement('th');
    thEliminar.scope = 'col';
    thEliminar.textContent = '';
    tr.appendChild(thEliminar);
    
}

var numeroFila = 1;

document.getElementById("agregarFila").addEventListener("click", function() {
    var numeroOpciones = obtenerNumeroOpciones();
    var table = document.getElementById("miTabla").getElementsByTagName('tbody')[0];
    var newRow = table.insertRow(table.rows.length - 1);

    var cell1 = newRow.insertCell(0);
    cell1.innerHTML = numeroFila++;

    var cell2 = newRow.insertCell(1);

    var inputContainer = document.createElement('div');
    inputContainer.classList.add('input-container');
    inputContainer.style.display = 'inline-block';
    inputContainer.style.width = '80%';

    var inputText = document.createElement('textarea');
    inputText.placeholder = 'Ingrese el texto aquí';
    inputText.classList.add('form-control');
    inputText.style.width = '100%';
    inputText.style.wordWrap = 'break-word';
    inputText.style.overflowWrap = 'break-word';
    inputText.maxLength = '100';
    inputContainer.appendChild(inputText);

    cell2.appendChild(inputContainer);

    for (var i = 0; i < numeroOpciones; i++) {
        var cell = newRow.insertCell(2 + i);

        var optionDiv = document.createElement('div');
        optionDiv.classList.add('option');
        optionDiv.style.display = 'flex';
        optionDiv.style.flexDirection = 'column';
        optionDiv.style.alignItems = 'center';

        var radioInput = document.createElement('input');
        radioInput.type = 'radio';
        radioInput.name = 'respuesta' + numeroFila;
        radioInput.value = i + 1;
        radioInput.classList.add('radio-input');

        var label = document.createElement('label');
        label.classList.add('option-container');

        label.appendChild(radioInput);
        var span = document.createElement('span');
        span.classList.add('option');
        label.appendChild(span);

        cell.appendChild(label);
    }

    var cellEliminar = newRow.insertCell(2 + numeroOpciones);
    var deleteButton = document.createElement('button');
    deleteButton.innerHTML = 'ELIMINAR';
    deleteButton.classList.add('btn', 'btn-danger');
    deleteButton.addEventListener('click', function() {
        var row = this.parentNode.parentNode;
        row.parentNode.removeChild(row);
    });
    cellEliminar.style.textAlign = 'right';
    cellEliminar.appendChild(deleteButton);
});

document.querySelectorAll('.inline-input')[1].addEventListener('input', actualizarEncabezadoTabla);

actualizarEncabezadoTabla();

function obtenerNombreMes(mes) {
    const nombresMeses = [
        'Enero', 'Febrero', 'Marzo', 'Abril',
        'Mayo', 'Junio', 'Julio', 'Agosto',
        'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
    ];
    return nombresMeses[mes - 1];
}

function obtenerFechaActual() {
    var fecha = new Date();
    var dia = String(fecha.getDate()).padStart(2, '0');
    var mes = String(fecha.getMonth() + 1).padStart(2, '0');
    var anio = fecha.getFullYear();
    return `${dia} de ${obtenerNombreMes(mes)} de ${anio}`;
}

function obtenerFechaISO(fechaTexto) {
    const meses = {
        'Enero': '01', 'Febrero': '02', 'Marzo': '03', 'Abril': '04',
        'Mayo': '05', 'Junio': '06', 'Julio': '07', 'Agosto': '08',
        'Septiembre': '09', 'Octubre': '10', 'Noviembre': '11', 'Diciembre': '12'
    };
    
    const partes = fechaTexto.split(' ');
    const dia = partes[0];
    const mes = meses[partes[2]];
    const anio = partes[4];

    return `${anio}-${mes}-${dia.padStart(2, '0')}`;
}

function asignarFechaActual() {
    var campoFecha = document.querySelector('td#campoFecha');
    if (campoFecha) {
        var fechaActual = obtenerFechaActual();
        campoFecha.textContent = fechaActual;
        console.log('Fecha actual asignada:', fechaActual);
    } else {
        console.error('No se encontró el campo de fecha con el ID campoFecha.');
    }
}

window.addEventListener('load', asignarFechaActual);

window.addEventListener('load', () => {
    setTimeout(asignarFechaActual, 100);
});

document.getElementById('CrearBtn').addEventListener('click', CrearNuevaEncuesta);
