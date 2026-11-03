<?php
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    // Verificar si el correo está registrado en usuarios
    $stmt = $conexion->prepare("SELECT id, usuario FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $usuario = $resultado->fetch_assoc();
        
        // Generar token único y fecha de expiración (1 hora)
        $token = bin2hex(random_bytes(32));
        $expira = date("Y-m-d H:i:s", strtotime('+1 hour'));

        // Limpiar tokens anteriores de este correo
        $stmt_del = $conexion->prepare("DELETE FROM password_resets WHERE email = ?");
        $stmt_del->bind_param("s", $email);
        $stmt_del->execute();
        $stmt_del->close();

        // Guardar el nuevo token
        $stmt_ins = $conexion->prepare("INSERT INTO password_resets (email, token, expira) VALUES (?, ?, ?)");
        $stmt_ins->bind_param("sss", $email, $token, $expira);
        $stmt_ins->execute();
        $stmt_ins->close();

        // Enlace de recuperación
        $enlace = "http://localhost/EventOnline/templates/restablecer_contrasena.php?token=" . $token;

        // Configuración del correo
        $asunto = "Recuperación de Contraseña - EventOnline";
        $mensaje = "Hola " . $usuario['usuario'] . ",\n\n" .
                   "Has solicitado restablecer tu contraseña en EventOnline.\n" .
                   "Haz clic en el siguiente enlace para crear una nueva contraseña:\n\n" . 
                   $enlace . "\n\n" .
                   "Este enlace expirará en 1 hora.\n" .
                   "Si no solicitaste este cambio, puedes ignorar este mensaje.";
                   
        $cabeceras = "From: no-reply@eventonline.com\r\n" .
                     "Reply-To: no-reply@eventonline.com\r\n" .
                     "X-Mailer: PHP/" . phpversion();

        // Enviar correo (Nota: En XAMPP local requiere configurar sendmail.ini con un servidor SMTP como Gmail, o funcionará si hay un servidor de correo activo)
        if (@mail($email, $asunto, $mensaje, $cabeceras)) {
            header("Location: ../templates/recuperar_contrasena.php?exito=Correo enviado con éxito. Revisa tu bandeja de entrada.");
            exit();
        } else {
            // Mensaje de ayuda si XAMPP no tiene el servicio de correo configurado localmente
            header("Location: ../templates/recuperar_contrasena.php?exito=Token generado. (Nota local: Configura sendmail en XAMPP para envío real de emails). Enlace de prueba: " . $enlace);
            exit();
        }
    } else {
        header("Location: ../templates/recuperar_contrasena.php?error=El correo electrónico no está registrado.");
        exit();
    }
    $stmt->close();
}
?>