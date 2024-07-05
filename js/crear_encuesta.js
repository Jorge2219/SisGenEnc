
function CrearNuevaEncuesta() {
    let questionsHtml = '<input id="swal-input1" class="swal2-input" placeholder="Título de la encuesta">';
    questionsHtml += '<input id="swal-input2" class="swal2-input" placeholder="Descripción de la encuesta">';
    questionsHtml += '<div id="questions-container"></div>';
    questionsHtml += '<button class="swal2-confirm swal2-styled" onclick="AgregarPregunta()">Agregar Pregunta</button>';

    Swal.fire({
        title: 'Crear Encuesta',
        html: questionsHtml,
        showCancelButton: true,
        confirmButtonText: 'Continuar',
        cancelButtonText: 'Cancelar',
        showLoaderOnConfirm: true,
        customClass: {
            popup: 'custom-swal-dialog'
        },
        preConfirm: () => {
            // Obtener los datos de la encuesta
            const title = document.getElementById('swal-input1').value;
            const description = document.getElementById('swal-input2').value;
            const questions = [];
            document.querySelectorAll('.swal2-question-input').forEach(input => {
                questions.push(input.value);
            });

            // Mostrar la vista previa de la encuesta
            MostrarVistaPrevia(title, description, questions);

            // Evitar que SweetAlert cierre automáticamente
            return false;
        }
    });
}

function MostrarVistaPrevia(title, description, questions) {
    let previewHtml = '<h2>Previsualización de la Encuesta</h2>';
    previewHtml += `<h3>${title}</h3>`;
    previewHtml += `<p>${description}</p>`;
    previewHtml += '<h4>Preguntas:</h4>';
    questions.forEach((question, index) => {
        previewHtml += `<p><strong>Pregunta ${index + 1}:</strong> ${question}</p>`;
    });

    Swal.fire({
        title: 'Vista Previa',
        html: previewHtml,
        confirmButtonText: 'Confirmar',
        showCancelButton: true,
        cancelButtonText: 'Editar',
        showLoaderOnConfirm: true,
        customClass: {
            popup: 'custom-swal-dialog'
        }
    }).then((result) => {
        // Si el usuario confirma, continuar con la creación de la encuesta
        if (result.isConfirmed) {
            GuardarEncuesta(title, description, questions);
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            // Si el usuario desea editar, permitir la edición de la encuesta
            CrearNuevaEncuesta();
        }
    });
}

function GuardarEncuesta(title, description, questions) {
    // Aquí puedes enviar los datos al servidor para guardarlos en la base de datos
    // Por ejemplo, utilizando AJAX o redirigiendo a una página de procesamiento en el servidor
    console.log("Guardando encuesta...");
    console.log("Título:", title);
    console.log("Descripción:", description);
    console.log("Preguntas:", questions);
}

function AgregarPregunta() {
    const questionsContainer = document.getElementById('questions-container');
    const questionInput = document.createElement('input');
    questionInput.classList.add('swal2-input', 'swal2-question-input');
    questionInput.placeholder = 'Pregunta';
    questionsContainer.appendChild(questionInput);
}

// Función para previsualizar la encuesta
document.getElementById('previewBtn').addEventListener('click', function() {
    const title = document.getElementById('swal-input1').value;
    const description = document.getElementById('swal-input2').value;
    const questions = [];
    document.querySelectorAll('.swal2-question-input').forEach(input => {
        questions.push(input.value);
    });

    function MostrarVistaPrevia(title, description, questions) {
        let previewHtml = `
            <html>
            <head>
                <title>Vista Previa de la Encuesta</title>
                <style>
                    /* Estilos CSS para el diseño predefinido */
                    body {
                        font-family: Arial, sans-serif;
                        background-color: #f8f9fa;
                        color: #333;
                        margin: 0;
                        padding: 20px;
                    }
                    .encuesta-container {
                        max-width: 600px;
                        margin: 0 auto;
                        background-color: #fff;
                        border: 1px solid #ccc;
                        border-radius: 5px;
                        padding: 20px;
                    }
                    h1 {
                        color: #007bff;
                    }
                    p {
                        margin-bottom: 10px;
                    }
                    .pregunta {
                        margin-bottom: 20px;
                    }
                </style>
            </head>
            <body>
                <div class="encuesta-container">
                    <h1>${title}</h1>
                    <p>${description}</p>
                    <h2>Preguntas:</h2>
        `;
        
        questions.forEach((question, index) => {
            previewHtml += `
                <div class="pregunta">
                    <strong>Pregunta ${index + 1}:</strong>
                    <p>${question}</p>
                </div>
            `;
        });
    
        previewHtml += `
                </div>
            </body>
            </html>
        `;
    
        // Abrir la vista previa en una nueva ventana o pestaña del navegador
        var ventanaPrevia = window.open("", "Vista Previa de la Encuesta", "width=800,height=600");
        ventanaPrevia.document.open();
        ventanaPrevia.document.write(previewHtml);
        ventanaPrevia.document.close();
    }
    
});
