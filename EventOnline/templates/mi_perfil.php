<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil - eventOnline</title>
    <link rel="stylesheet" href="../static/css/perfil.css">
    <link rel="stylesheet" href="../static/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
<div class="profile-container">
    <h2>Mi Perfil</h2>
    <i class="fas fa-user profile-icon"></i>
    <div class="profile-item">
        <i class="fas fa-user"></i>
        <span>Usuario:</span> <?php echo htmlspecialchars($_SESSION['usuario']); ?>
    </div>
    <div class="profile-item">
        <i class="fas fa-envelope"></i>
        <span>Correo:</span> <?php echo isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : 'No especificado'; ?>
    </div>
    <div class="profile-item">
        <i class="fas fa-shield-alt"></i>
        <span>Rol:</span> <?php echo isset($_SESSION['rol']) ? strtoupper(htmlspecialchars($_SESSION['rol'])) : 'USUARIO'; ?>
    </div>
    <a href="index.php" class="btn-back">Volver a Inicio</a>
</div>
</body>
</html>