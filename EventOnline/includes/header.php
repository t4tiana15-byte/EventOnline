<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EventOnline</title>
    <link rel="stylesheet" href="../static/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header>
        <div class="header-container">
            <div class="logo-group">
                <button class="menu-toggle" id="menuToggle" aria-label="Abrir menú">
                    <i class="fas fa-bars"></i>
                </button>
                <a href="index.php" class="logo"><i class="fas fa-ticket-alt"></i> EventOnline</a>
            </div>

            <nav class="nav-links" id="navLinks">
                <a href="index.php#hero">Inicio</a>
                <a href="index.php#sitios">Sitios</a>
                <a href="index.php#servicios">Servicios Especiales</a>
                <a href="index.php#contactenos">Contacto</a>
            </nav>

            <div class="header-actions">
                <button class="btn-cart" id="cartToggle" title="Ver Carrito">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="cart-badge" id="cartBadge">0</span>
                </button>

                <div class="dropdown">
                    <a href="javascript:void(0)" class="dropbtn">
                        <?php echo isset($_SESSION['usuario']) ? strtoupper(substr($_SESSION['usuario'], 0, 1)) : '<i class="fas fa-user"></i>'; ?>
                    </a>
                    <div class="dropdown-content">
                        <?php if (isset($_SESSION['usuario'])): ?>
                            <a href="mi_perfil.php"><i class="fas fa-user-circle"></i> Mi Perfil</a>
                            <a href="mis_reservas.php"><i class="fas fa-calendar-check"></i> Mis Reservas</a>
                            <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
                                <a href="../admin/panel.php" class="admin-link-menu"><i class="fas fa-shield-alt"></i> Panel Admin</a>
                            <?php endif; ?>
                            <a href="../php/logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar sesión</a>
                        <?php else: ?>
                            <a href="login.php"><i class="fas fa-sign-in-alt"></i> Iniciar sesión</a>
                            <a href="register.php"><i class="fas fa-user-plus"></i> Registrarse</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </header>