<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarse - eventOnline</title>
    <link rel="stylesheet" href="../static/css/styles.css">
</head>
<body class="pagina-autenticacion">
    <div id="contenedor">
        <h2>Crear Cuenta</h2>
        <?php if (isset($_GET['error'])): ?>
            <div class="error-box"><p><?php echo htmlspecialchars($_GET['error']); ?></p></div>
        <?php endif; ?>
        <form action="../php/procesar_registro.php" method="POST" class="formulario">
            <div id="grupo-campo">
                <label>Nombre de usuario</label>
                <input type="text" name="usuario" required placeholder="Elige tu nombre de usuario">
            </div>
            <div id="grupo-campo">
                <label>Correo electrónico</label>
                <input type="email" name="email" required placeholder="tu@correo.com">
            </div>
            <div id="grupo-campo">
                <label>Contraseña</label>
                <input type="password" name="contrasena" required minlength="6" placeholder="Mínimo 6 caracteres">
            </div>
            <div id="grupo-campo">
                <label>Tipo de cuenta (Rol)</label>
                <select name="rol" required>
                    <option value="user">Usuario Regular</option>
                    <option value="admin">Administrador</option>
                </select>
            </div>
            <button type="submit" id="boton">Registrarme</button>
        </form>
        <p style="margin-top: 20px; text-align: center;">¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a></p>
    </div>
</body>
</html>