<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña</title>
    <link rel="stylesheet" href="../static/css/contrasena.css">
    <link rel="stylesheet" href="../static/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="pagina-autenticacion">
    <div id="contenedor">
        <h2>Recuperar Contraseña</h2>
        <?php if (isset($_GET['mensaje'])): ?>
            <div class="success-box"><p><?php echo htmlspecialchars($_GET['mensaje']); ?></p></div>
        <?php endif; ?>
        <form action="../php/procesar_recuperar.php" method="POST">
            <div id="grupo-campo">
                <label>Correo electrónico:</label>
                <input type="email" name="email" required placeholder="Ingresa tu correo">
            </div>
            <button type="submit" id="boton">Enviar instrucciones</button>
        </form>
        <p class="forgot-password"><a href="login.php">Volver al inicio de sesión</a></p>
    </div>
</body>
</html>