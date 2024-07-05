<?php
// Conexión a la base de datos
$host = 'localhost'; // Cambia según tu configuración
$user = 'id22332073_root'; // Cambia según tu configuración
$password = 'Diego&23'; // Cambia según tu configuración
$database = 'id22332073_login'; // Cambia según tu configuración

$conexion = mysqli_connect($host, $user, $password, $database);

// Verificar la conexión
if (mysqli_connect_errno()) {
    die("Error al conectar con la base de datos: " . mysqli_connect_error());
}

// Consulta para obtener períodos únicos de la columna 'periodo' en la tabla 'encuestas'
$query = "SELECT DISTINCT periodo FROM encuestas";

$result = mysqli_query($conexion, $query);

if (!$result) {
    die("Error al ejecutar la consulta: " . mysqli_error($conexion));
}

// Construir un array con los períodos
$periodos = array();
while ($row = mysqli_fetch_assoc($result)) {
    $periodos[] = $row['periodo'];
}

// Devolver los períodos como JSON
header('Content-Type: application/json');
echo json_encode($periodos);

// Cerrar la conexión
mysqli_close($conexion);
?>
