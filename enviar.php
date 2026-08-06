<?php
// Configuración de respuesta en formato JSON
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 1. Datos de conexión a MySQL en XAMPP
    $host     = "localhost"; // Usamos IP para evitar bloqueos por socket en macOS
    $user     = "miguel-guty23@hotmail.com";      // Usuario predeterminado de XAMPP
    $password = "Caballo+23";          // Por defecto en XAMPP la clave está vacía
    $dbname   = "guthyfoo_gutyfood"; // Nombre de tu base de datos

    // Crear conexión
    $conexion = new mysqli($host, $user, $password, $dbname);

    // Verificar si hay error en la conexión
    if ($conexion->connect_error) {
        echo json_encode([
            'status'  => 'error',
            'mensaje' => 'Error de conexión a la base de datos: ' . $conexion->connect_error
        ]);
        exit;
    }

    // Asegurar codificación UTF-8 para tildes y caracteres especiales
    $conexion->set_charset("utf8mb4");

    // 2. Sanitización y validación de datos recibidos del formulario
    $email       = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $nombre      = htmlspecialchars(trim($_POST['nombre'] ?? ''), ENT_QUOTES, 'UTF-8');
    $celular     = htmlspecialchars(trim($_POST['celular'] ?? ''), ENT_QUOTES, 'UTF-8');
    $comentarios = htmlspecialchars(trim($_POST['comentarios'] ?? ''), ENT_QUOTES, 'UTF-8');

    if (empty($email) || empty($nombre) || empty($celular)) {
        echo json_encode([
            'status'  => 'error',
            'mensaje' => 'Por favor, completa todos los campos obligatorios.'
        ]);
        exit;
    }

    // 3. Preparar la consulta SQL de inserción
    $sql = "INSERT INTO contactos (email, nombre, celular, comentarios) VALUES (?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql);

    if ($stmt) {
        // Enlazar parámetros: 'ssss' indica cuatro datos de tipo cadena (string)
        $stmt->bind_param("ssss", $email, $nombre, $celular, $comentarios);

        if ($stmt->execute()) {
            echo json_encode([
                'status'  => 'success',
                'mensaje' => '¡Tu mensaje ha sido guardado exitosamente en la base de datos!'
            ]);
        } else {
            echo json_encode([
                'status'  => 'error',
                'mensaje' => 'No se pudo guardar la información: ' . $stmt->error
            ]);
        }

        $stmt->close();
    } else {
        echo json_encode([
            'status'  => 'error',
            'mensaje' => 'Error en la preparación de la consulta SQL: ' . $conexion->error
        ]);
    }

    $conexion->close();

} else {
    echo json_encode([
        'status'  => 'error',
        'mensaje' => 'Acceso no permitido.'
    ]);
}
?>