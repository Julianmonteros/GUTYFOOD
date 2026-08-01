
<?php
// Configuramos la respuesta en formato JSON
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitizamos los campos para evitar inyecciones de código malicioso
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $nombre = htmlspecialchars(trim($_POST['nombre']), ENT_QUOTES, 'UTF-8');
    $celular = htmlspecialchars(trim($_POST['celular']), ENT_QUOTES, 'UTF-8');
    $comentarios = htmlspecialchars(trim($_POST['comentarios']), ENT_QUOTES, 'UTF-8');

    // Validación básica en el servidor
    if (empty($email) || empty($nombre) || empty($celular)) {
        echo json_encode([
            'status' => 'error',
            'mensaje' => 'Por favor, completa todos los campos obligatorios.'
        ]);
        exit;
    }

    // =======================================================
    // CONFIGURACIÓN DE TU CORREO
    // =======================================================
    $destinatario = "tu-correo@tuservidor.com"; // <-- ¡CAMBIA ESTO por tu correo real!
    $asunto = "Nuevo mensaje de contacto de Guthyfood";

    // Estructura del mensaje de correo
    $cuerpoMensaje = "Has recibido un nuevo mensaje desde el sitio web Guthyfood:\n\n";
    $cuerpoMensaje .= "Nombre: " . $nombre . "\n";
    $cuerpoMensaje .= "Email: " . $email . "\n";
    $cuerpoMensaje .= "Celular: " . $celular . "\n";
    $cuerpoMensaje .= "Comentarios:\n" . $comentarios . "\n";

    // Cabeceras del correo (Headers)
    $headers = "From: no-reply@guthyfood.com\r\n"; // Remitente aparente
    $headers .= "Reply-To: " . $email . "\r\n";  // Al responder, le responderás al cliente
    $headers .= "X-Mailer: PHP/" . phpversion();

    // Enviamos el correo mediante la función nativa mail() de PHP
    if (mail($destinatario, $asunto, $cuerpoMensaje, $headers)) {
        echo json_encode([
            'status' => 'success',
            'mensaje' => '¡Tu mensaje ha sido enviado con éxito! Nos comunicaremos pronto.'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'mensaje' => 'El servidor no pudo enviar el correo. Inténtalo de nuevo.'
        ]);
    }
} else {
    // Si intentan entrar directo a enviar.php sin enviar datos
    echo json_encode([
        'status' => 'error',
        'mensaje' => 'Acceso no permitido.'
    ]);
}