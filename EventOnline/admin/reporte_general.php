<?php
session_start();
include '../php/conexion.php';

// Verificar que el usuario tenga rol de administrador
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../templates/login.php");
    exit();
}

// 1. Consultar Reservas
$res_reservas = $conexion->query("SELECT r.id, u.usuario AS cliente, r.item_nombre, r.fecha, r.hora, r.metodo_pago, r.estado FROM reservas r JOIN usuarios u ON r.usuario_id = u.id ORDER BY r.fecha DESC");

// 2. Consultar Usuarios
$res_usuarios = $conexion->query("SELECT id, usuario, email, rol FROM usuarios ORDER BY id DESC");

// 3. Consultar Sitios
$res_sitios = $conexion->query("SELECT s.id, s.nombre, s.ubicacion, s.precio, u.usuario AS propietario FROM sitios s LEFT JOIN usuarios u ON s.propietario_id = u.id ORDER BY s.id DESC");

// 4. Consultar Servicios
$res_servicios = $conexion->query("SELECT serv.id, serv.nombre, serv.precio, u.usuario AS propietario FROM servicios serv LEFT JOIN usuarios u ON serv.propietario_id = u.id ORDER BY serv.id DESC");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte General - EventOnline</title>
    <link rel="stylesheet" href="../static/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .report-section { margin-top: 40px; }
        .report-subtitle { color: #080808; font-size: 1.4rem; margin-bottom: 15px; border-bottom: 2px solid #4f46e5; padding-bottom: 8px; display: flex; align-items: center; gap: 10px; }
    </style>
</head>
<body class="admin-body">
    <div class="admin-container admin-container-wide">
        <!-- Encabezado Principal -->
        <div class="admin-header-flex">
            <h2 class="admin-title"><i class="fas fa-file-invoice"></i> Reporte Maestro / Todo en Uno</h2>
            <div>
                <button onclick="window.print();" class="cta-button admin-btn-header" style="margin-right: 10px;"><i class="fas fa-print"></i> Imprimir / PDF General</button>
                <a href="panel.php" class="cta-button admin-btn-header">Volver al Panel</a>
            </div>
        </div>
        <p style="color: #cbd5e1; margin-bottom: 20px;">Resumen completo de la plataforma EventOnline: Reservas, Usuarios, Sitios y Servicios registrados.</p>

        <!-- SECCIÓN 1: RESERVAS -->
        <div class="report-section">
            <div class="report-subtitle"><i class="fas fa-calendar-check" style="color: #8b5cf6;"></i> 1. Historial de Reservas</div>
            <div class="admin-table-wrapper">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Item</th>
                            <th>Fecha y Hora</th>
                            <th>Pago</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($res_reservas && $res_reservas->num_rows > 0): ?>
                            <?php while ($row = $res_reservas->fetch_assoc()): ?>
                                <tr>
                                    <td>#<?php echo $row['id']; ?></td>
                                    <td class="admin-td-bold"><?php echo htmlspecialchars($row['cliente']); ?></td>
                                    <td><?php echo htmlspecialchars($row['item_nombre']); ?></td>
                                    <td><?php echo htmlspecialchars($row['fecha'] . ' ' . $row['hora']); ?></td>
                                    <td><span class="card-badge"><?php echo htmlspecialchars($row['metodo_pago']); ?></span></td>
                                    <td style="color: #10b981; font-weight: bold;"><?php echo htmlspecialchars($row['estado']); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="6" class="admin-td-empty">No hay reservas registradas.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- SECCIÓN 2: USUARIOS -->
        <div class="report-section">
            <div class="report-subtitle"><i class="fas fa-users" style="color: #06b6d4;"></i> 2. Usuarios del Sistema</div>
            <div class="admin-table-wrapper">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Usuario</th>
                            <th>Correo Electrónico</th>
                            <th>Rol</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($res_usuarios && $res_usuarios->num_rows > 0): ?>
                            <?php while ($row = $res_usuarios->fetch_assoc()): ?>
                                <tr>
                                    <td>#<?php echo $row['id']; ?></td>
                                    <td class="admin-td-bold"><?php echo htmlspecialchars($row['usuario']); ?></td>
                                    <td><?php echo htmlspecialchars($row['email'] ?? 'N/A'); ?></td>
                                    <td><span class="card-badge"><?php echo htmlspecialchars($row['rol']); ?></span></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="4" class="admin-td-empty">No hay usuarios registrados.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- SECCIÓN 3: SITIOS -->
        <div class="report-section">
            <div class="report-subtitle"><i class="fas fa-building" style="color: #f59e0b;"></i> 3. Sitios y Salones Registrados</div>
            <div class="admin-table-wrapper">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre del Sitio</th>
                            <th>Ubicación</th>
                            <th>Precio</th>
                            <th>Propietario</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($res_sitios && $res_sitios->num_rows > 0): ?>
                            <?php while ($row = $res_sitios->fetch_assoc()): ?>
                                <tr>
                                    <td>#<?php echo $row['id']; ?></td>
                                    <td class="admin-td-bold"><?php echo htmlspecialchars($row['nombre']); ?></td>
                                    <td><?php echo htmlspecialchars($row['ubicacion']); ?></td>
                                    <td>$<?php echo number_format($row['precio'], 2); ?></td>
                                    <td><?php echo htmlspecialchars($row['propietario'] ?? 'Sistema'); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="5" class="admin-td-empty">No hay sitios registrados.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- SECCIÓN 4: SERVICIOS -->
        <div class="report-section" style="margin-bottom: 50px;">
            <div class="report-subtitle"><i class="fas fa-concierge-bell" style="color: #ec4899;"></i> 4. Servicios Registrados</div>
            <div class="admin-table-wrapper">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre del Servicio</th>
                            <th>Precio</th>
                            <th>Propietario</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($res_servicios && $res_servicios->num_rows > 0): ?>
                            <?php while ($row = $res_servicios->fetch_assoc()): ?>
                                <tr>
                                    <td>#<?php echo $row['id']; ?></td>
                                    <td class="admin-td-bold"><?php echo htmlspecialchars($row['nombre']); ?></td>
                                    <td>$<?php echo number_format($row['precio'], 2); ?></td>
                                    <td><?php echo htmlspecialchars($row['propietario'] ?? 'Sistema'); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="4" class="admin-td-empty">No hay servicios registrados.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>