<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - eventOnline</title>
    <link rel="stylesheet" href="../static/css/styles.css">
</head>
<body class="pagina-autenticacion">
    <div id="contenedor">
        <h2>Iniciar Sesión</h2>
        <?php if (isset($_GET['error'])): ?>
            <div class="error-box"><p><?php echo htmlspecialchars($_GET['error']); ?></p></div>
        <?php endif; ?>
        <form action="../php/procesar_login.php" method="POST" class="formulario">
            <div id="grupo-campo">
                <label>Nombre de usuario</label>
                <input type="text" name="usuario" required placeholder="Ingresa tu usuario">
            </div>
            <div id="grupo-campo">
                <label>Contraseña</label>
                <input type="password" name="contrasena" required placeholder="Ingresa tu contraseña">
            </div>
            <button type="submit" id="boton">Entrar</button>
        </form>
        <p class="forgot-password"><a href="recuperar_contrasena.php">¿Olvidaste tu contraseña?</a></p>
        <p style="margin-top: 20px; text-align: center;">¿No tienes cuenta? <a href="register.php">Crea una ahora aquí!</a></p>
    </div>
</body>
</html>