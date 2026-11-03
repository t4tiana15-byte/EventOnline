<?php
include '../php/conexion.php';

$token = isset($_GET['token']) ? $_GET['token'] : '';
$valido = false;
$mensaje = "";

if (!empty($token)) {
    $stmt = $conexion->prepare("SELECT email, expira FROM password_resets WHERE token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        $row = $res->fetch_assoc();
        if (strtotime($row['expira']) > time()) {
            $valido = true;
            $email_usuario = $row['email'];
        } else {
            $mensaje = "El enlace de recuperación ha expirado.";
        }
    } else {
        $mensaje = "Token de recuperación inválido.";
    }
    $stmt->close();
} else {
    $mensaje = "No se proporcionó ningún token.";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer Contraseña - EventOnline</title>
    <link rel="stylesheet" href="../static/css/styles.css">
    <link rel="stylesheet" href="../static/css/restablecer.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="admin-body">
    <div class="admin-container" style="max-width: 400px; margin: 80px auto;">
        <h2 class="admin-title"><i class="fas fa-key"></i> Nueva Contraseña</h2>
        
        <?php if ($valido): ?>
            <form action="../php/procesar_restablecer.php" method="POST" class="formulario">
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                <input type="hidden" name="email" value="<?php echo htmlspecialchars($email_usuario); ?>">
                
                <div id="grupo-campo">
                    <label>Nueva Contraseña</label>
                    <input type="password" name="nueva_contrasena" required placeholder="Mínimo 6 caracteres">
                </div>
                <button type="submit" id="boton">Actualizar Contraseña</button>
            </form>
        <?php else: ?>
            <div class="admin-alert" style="background-color: #c444ef; color: white;"><?php echo htmlspecialchars($mensaje); ?></div>
            <p style="text-align: center; margin-top: 20px;"><a href="recuperar_contrasena.php" class="forgot-password">Solicitar nuevo enlace</a></p>
        <?php endif; ?>
    </div>
</body>
</html>