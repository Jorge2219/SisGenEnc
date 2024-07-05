<?php
// Verificar si se recibe un ID válido
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $idUsuario = $_GET['id'];

    // Aquí debes realizar la lógica para obtener la información del usuario con el ID proporcionado
    // Esto podría incluir una consulta a la base de datos
    // Verificar si se recibe un ID válido
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $idUsuario = $_GET['id'];

    // Conectar a la base de datos (Ajusta estos valores según tu configuración)
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "login";

    $conexion = new mysqli($servername, $username, $password, $dbname);

    // Verificar la conexión
    if ($conexion->connect_error) {
        die("Conexión fallida: " . $conexion->connect_error);
    }

    // Consulta para obtener la información del usuario
    $sql = "SELECT * FROM usuario WHERE id = $idUsuario";
    $result = $conexion->query($sql);

    // Verificar si se obtuvo un resultado
    if ($result->num_rows > 0) {
        // Obtener los datos del usuario
        $usuario = $result->fetch_assoc();

        // Devolver la información del usuario en formato JSON
        echo json_encode(['success' => true, 'usuario' => $usuario]);
    } else {
        // No se encontró el usuario con el ID proporcionado
        echo json_encode(['success' => false, 'message' => 'Usuario no encontrado']);
    }

    // Cerrar la conexión
    $conexion->close();
} else {
    // ID de usuario no válido
    echo json_encode(['success' => false, 'message' => 'ID de usuario no válido']);
}

    // Simulando una respuesta exitosa
    $usuario = [
        'nombre' => 'Nombre Actual',
        'apellidos' => 'Apellidos Actuales',
        'Area' => 'Área Actual'
    ];

    echo json_encode(['success' => true, 'usuario' => $usuario]);
} else {
    echo json_encode(['success' => false, 'message' => 'ID de usuario no válido']);
}
?>
