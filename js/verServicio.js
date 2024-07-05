window.onload = function () {
    // solicitud AJAX para obtener los servicios almacenados
    $.ajax({
        type: "GET",
        url: "obtener_servicio.php", //  script PHP que obtiene los servicios desde la base de datos
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
                `;
                // Añade el ID del servicio como un atributo de datos
                nuevoBox.setAttribute("data-id", servicio.id);

                // Añade un evento de clic a la caja
                nuevoBox.addEventListener("click", function() {
                    var servicioId = this.getAttribute("data-id");
                    var nombre = encodeURIComponent(servicio.nombre);
                    var color = encodeURIComponent(servicio.color);
                    var icono = encodeURIComponent(servicio.icono);
                    window.location.href = `abrir_encuestas.php?id=${servicioId}&nombre=${nombre}`;
                });

                // Funciones para determinar si el color es oscuro
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
            });
        },
        error: function(jqXHR, textStatus, errorThrown) {
            // Maneja el error de la solicitud AJAX
            console.error("Error en la solicitud AJAX:", textStatus, errorThrown);
        }
    });
};
