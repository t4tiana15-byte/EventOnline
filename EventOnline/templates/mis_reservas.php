<?php
session_start();
include '../php/conexion.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

$usuario_actual = $_SESSION['usuario'];
$stmt_u = $conexion->prepare("SELECT id FROM usuarios WHERE usuario = ?");
$stmt_u->bind_param("s", $usuario_actual);
$stmt_u->execute();
$my_id = $stmt_u->get_result()->fetch_assoc()['id'];
$stmt_u->close();

$mensaje = "";

// Procesar cancelación de reserva
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancelar_id'])) {
    $reserva_id = intval($_POST['cancelar_id']);
    $stmt_del = $conexion->prepare("DELETE FROM reservas WHERE id = ? AND usuario_id = ?");
    $stmt_del->bind_param("ii", $reserva_id, $my_id);
    if ($stmt_del->execute()) {
        $mensaje = "Reserva cancelada con éxito.";
    }
    $stmt_del->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Reservas - EventOnline</title>
    <link rel="stylesheet" href="../static/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="admin-body">
    <div class="admin-container admin-container-wide">
        <div class="admin-header-flex">
            <h2 class="admin-title"><i class="fas fa-calendar-check"></i> Mis Reservas y Pedidos</h2>
            <a href="index.php" class="cta-button admin-btn-header">Volver al Inicio</a>
        </div>

        <?php if (!empty($mensaje)): ?>
            <div class="admin-alert"><?php echo htmlspecialchars($mensaje); ?></div>
        <?php endif; ?>

        <div class="admin-table-wrapper" style="margin-top: 20px;">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Servicio / Sitio</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Método de Pago</th>
                        <th>Estado</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $stmt_res = $conexion->prepare("SELECT * FROM reservas WHERE usuario_id = ? ORDER BY fecha DESC");
                    $stmt_res->bind_param("i", $my_id);
                    $stmt_res->execute();
                    $resultado = $stmt_res->get_result();

                    if ($resultado && $resultado->num_rows > 0) {
                        while ($row = $resultado->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td class="admin-td-bold">' . htmlspecialchars($row['item_nombre']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['fecha']) . '</td>';
                            echo '<td>' . htmlspecialchars($row['hora']) . '</td>';
                            echo '<td><span class="card-badge">' . htmlspecialchars($row['metodo_pago']) . '</span></td>';
                            echo '<td style="color: #10b981; font-weight: bold;">' . htmlspecialchars($row['estado']) . '</td>';
                            echo '<td>';
                            echo '<form action="mis_reservas.php" method="POST" onsubmit="return confirm(\'¿Estás seguro de cancelar esta reserva?\');">';
                            echo '<input type="hidden" name="cancelar_id" value="' . $row['id'] . '">';
                            echo '<button type="submit" class="admin-btn-delete"><i class="fas fa-times-circle"></i> Cancelar</button>';
                            echo '</form>';
                            echo '</td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="6" class="admin-td-empty">No tienes reservas activas en este momento.</td></tr>';
                    }
                    $stmt_res->close();
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>