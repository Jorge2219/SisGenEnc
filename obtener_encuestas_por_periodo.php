<?php
include 'conexion.php'; // Verificar y incluir el archivo de conexión

$periodo = $_GET['periodo']; // Obtener el periodo seleccionado desde AJAX

$query = "SELECT id, titulo FROM encuestas";

// Si se selecciona un periodo específico, ajustar la consulta
if ($periodo !== 'todos') {
    $query .= " WHERE periodo = '$periodo'";
}

$result = $conexion->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row["id"] . "</td>";
        echo "<td>" . $row["titulo"] . "</td>";
        echo "<td class='acciones-column'>
                <form action='generar_reporte.php' method='GET' style='display: inline-block;'>
                    <input type='hidden' name='encuesta_id' value='" . $row["id"] . "'>
                    <button class='btn-reporte' type='submit'>Generar Pdf</button>
                </form>
                <form action='generar_excel.php' method='GET' style='display: inline-block;'>
                    <input type='hidden' name='encuesta_id' value='" . $row["id"] . "'>
                    <button class='btn-excel' type='submit'>Generar Excel</button>
                </form>
                <form action='generar_word.php' method='GET' style='display: inline-block;'>
                    <input type='hidden' name='encuesta_id' value='" . $row["id"] . "'>
                    <button class='btn-word' type='submit'>Generar Word</button>
                </form>
              </td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='3'>No hay encuestas disponibles para este periodo.</td></tr>";
}

$conexion->close();
?>
