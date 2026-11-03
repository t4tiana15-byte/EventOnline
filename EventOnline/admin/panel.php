<?php
session_start();
include '../php/conexion.php';

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../templates/login.php?error=Acceso no autorizado");
    exit();
}

// Obtener ID del admin actual
$usuario_actual = $_SESSION['usuario'];
$stmt_user = $conexion->prepare("SELECT id FROM usuarios WHERE usuario = ?");
$stmt_user->bind_param("s", $usuario_actual);
$stmt_user->execute();
$res_user = $stmt_user->get_result();
$user_data = $res_user->fetch_assoc();
$my_user_id = $user_data['id'];
$stmt_user->close();

$mensaje = "";

// 1. Procesar creación de Sitio
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'crear_sitio') {
    $nombre = trim($_POST['nombre']);
    $descripcion = trim($_POST['descripcion']);
    $precio = floatval($_POST['precio']);
    
    $imagen_ruta = "";
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $nombre_archivo = time() . '_' . basename($_FILES['imagen']['name']);
        $ruta_destino = '../static/img/' . $nombre_archivo;
        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_destino)) {
            $imagen_ruta = '../static/img/' . $nombre_archivo;
        }
    }

    if (!empty($nombre) && !empty($descripcion) && !empty($imagen_ruta) && $precio > 0) {
        $stmt = $conexion->prepare("INSERT INTO sitios (usuario_id, nombre, descripcion, imagen, precio) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("isssd", $my_user_id, $nombre, $descripcion, $imagen_ruta, $precio);
        if ($stmt->execute()) { $mensaje = "¡Sitio publicado con éxito!"; }
        $stmt->close();
    } else { $mensaje = "Completa todos los campos del sitio correctamente."; }
}

// 2. Procesar borrado de Sitio
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'borrar_sitio') {
    $sitio_id = intval($_POST['sitio_id']);
    $stmt_del = $conexion->prepare("DELETE FROM sitios WHERE id = ? AND usuario_id = ?");
    $stmt_del->bind_param("ii", $sitio_id, $my_user_id);
    if ($stmt_del->execute()) { $mensaje = "¡Sitio eliminado!"; }
    $stmt_del->close();
}

// 3. Procesar creación de Servicio (Animación, decoración, serenata, etc.)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'crear_servicio') {
    $nombre = trim($_POST['nombre']);
    $descripcion = trim($_POST['descripcion']);
    $precio = floatval($_POST['precio']);
    
    $imagen_ruta = "";
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $nombre_archivo = 'serv_' . time() . '_' . basename($_FILES['imagen']['name']);
        $ruta_destino = '../static/img/' . $nombre_archivo;
        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_destino)) {
            $imagen_ruta = '../static/img/' . $nombre_archivo;
        }
    }

    if (!empty($nombre) && !empty($descripcion) && !empty($imagen_ruta) && $precio > 0) {
        $stmt = $conexion->prepare("INSERT INTO servicios (usuario_id, nombre, descripcion, imagen, precio) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("isssd", $my_user_id, $nombre, $descripcion, $imagen_ruta, $precio);
        if ($stmt->execute()) { $mensaje = "¡Servicio publicado con éxito!"; }
        $stmt->close();
    } else { $mensaje = "Completa todos los campos del servicio correctamente."; }
}

// 4. Procesar borrado de Servicio
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['accion']) && $_POST['accion'] === 'borrar_servicio') {
    $servicio_id = intval($_POST['servicio_id']);
    $stmt_del = $conexion->prepare("DELETE FROM servicios WHERE id = ? AND usuario_id = ?");
    $stmt_del->bind_param("ii", $servicio_id, $my_user_id);
    if ($stmt_del->execute()) { $mensaje = "¡Servicio eliminado!"; }
    $stmt_del->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - EventOnline</title>
    <link rel="stylesheet" href="../static/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="admin-body">
    <div class="admin-container admin-container-wide">
        <div class="admin-header-flex">
            <h2 class="admin-title"><i class="fas fa-shield-alt"></i> Panel de Administración</h2>
            <a href="../templates/index.php" class="cta-button admin-btn-header">Ver Sitio Web</a>
        </div>
        
        <p>Bienvenido, <strong><?php echo htmlspecialchars($_SESSION['usuario']); ?></strong>. Gestiona tus espacios y servicios personalizados (animadores, decoración, serenata, etc.).</p>
        
        <?php if (!empty($mensaje)): ?>
            <div class="admin-alert"><?php echo htmlspecialchars($mensaje); ?></div>
        <?php endif; ?>

        <hr class="admin-divider">

        <!-- FORMULARIO 1: CREAR SITIO -->
        <h3 class="admin-subtitle"><i class="fas fa-map-marker-alt"></i> Registrar Nuevo Sitio / Salón</h3>
        <form action="panel.php" method="POST" enctype="multipart/form-data" class="formulario admin-form-box">
            <input type="hidden" name="accion" value="crear_sitio">
            <div id="grupo-campo">
                <label>Nombre del Sitio</label>
                <input type="text" name="nombre" required placeholder="Ej: Salón Imperial">
            </div>
            <div id="grupo-campo">
                <label>Descripción</label>
                <input type="text" name="descripcion" required placeholder="Ej: Espacio exclusivo para grandes galas.">
            </div>
            <div id="grupo-campo">
                <label>Precio de Alquiler ($)</label>
                <input type="number" name="precio" step="1000" required placeholder="Ej: 1500000">
            </div>
            <div id="grupo-campo">
                <label>Imagen del Sitio</label>
                <input type="file" name="imagen" accept="image/*" required class="admin-file-input">
            </div>
            <button type="submit" id="boton">Guardar Sitio</button>
        </form>

        <hr class="admin-divider">

        <!-- TABLA DE MIS SITIOS -->
        <h3 class="admin-subtitle"><i class="fas fa-list"></i> Tus Sitios Registrados</h3>
        <div class="admin-table-wrapper">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Imagen</th>
                        <th>Nombre</th>
                        <th>Precio</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $stmt_s = $conexion->prepare("SELECT * FROM sitios WHERE usuario_id = ?");
                    $stmt_s->bind_param("i", $my_user_id);
                    $stmt_s->execute();
                    $res_s = $stmt_s->get_result();
                    if ($res_s && $res_s->num_rows > 0) {
                        while ($row = $res_s->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td><img src="' . htmlspecialchars($row['imagen']) . '" class="admin-thumb"></td>';
                            echo '<td class="admin-td-bold">' . htmlspecialchars($row['nombre']) . '</td>';
                            echo '<td class="admin-td-bold" style="color: var(--accent-purple);">$' . number_format($row['precio'], 0, ',', '.') . '</td>';
                            echo '<td><div class="admin-actions-cell">';
                            echo '<a href="editar_sitio.php?id=' . $row['id'] . '" class="admin-btn-edit"><i class="fas fa-edit"></i> Editar</a>';
                            echo '<form action="panel.php" method="POST" onsubmit="return confirm(\'¿Eliminar este sitio?\');" style="display:inline;">';
                            echo '<input type="hidden" name="accion" value="borrar_sitio">';
                            echo '<input type="hidden" name="sitio_id" value="' . $row['id'] . '">';
                            echo '<button type="submit" class="admin-btn-delete"><i class="fas fa-trash"></i> Borrar</button>';
                            echo '</form>';
                            echo '</div></td></tr>';
                        }
                    } else {
                        echo '<tr><td colspan="4" class="admin-td-empty">No tienes sitios registrados.</td></tr>';
                    }
                    $stmt_s->close();
                    ?>
                </tbody>
            </table>
        </div>

        <hr class="admin-divider" style="margin: 40px 0;">

        <!-- FORMULARIO 2: CREAR SERVICIO -->
        <h3 class="admin-subtitle"><i class="fas fa-concierge-bell"></i> Registrar Nuevo Servicio (Animador, Decoración, Serenata, etc.)</h3>
        <form action="panel.php" method="POST" enctype="multipart/form-data" class="formulario admin-form-box">
            <input type="hidden" name="accion" value="crear_servicio">
            <div id="grupo-campo">
                <label>Nombre del Servicio</label>
                <input type="text" name="nombre" required placeholder="Ej: Mariジャケット Serenata / Animador Profesional / Decoración Floral">
            </div>
            <div id="grupo-campo">
                <label>Descripción</label>
                <input type="text" name="descripcion" required placeholder="Ej: Show en vivo de 1 hora con repertorio romántico.">
            </div>
            <div id="grupo-campo">
                <label>Precio del Servicio ($)</label>
                <input type="number" name="precio" step="1000" required placeholder="Ej: 300000">
            </div>
            <div id="grupo-campo">
                <label>Imagen del Servicio</label>
                <input type="file" name="imagen" accept="image/*" required class="admin-file-input">
            </div>
            <button type="submit" id="boton">Guardar Servicio</button>
        </form>

        <hr class="admin-divider">

        <!-- TABLA DE MIS SERVICIOS -->
        <h3 class="admin-subtitle"><i class="fas fa-list"></i> Tus Servicios Registrados</h3>
        <div class="admin-table-wrapper">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Imagen</th>
                        <th>Nombre</th>
                        <th>Precio</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $stmt_serv = $conexion->prepare("SELECT * FROM servicios WHERE usuario_id = ?");
                    $stmt_serv->bind_param("i", $my_user_id);
                    $stmt_serv->execute();
                    $res_serv = $stmt_serv->get_result();
                    if ($res_serv && $res_serv->num_rows > 0) {
                        while ($row = $res_serv->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td><img src="' . htmlspecialchars($row['imagen']) . '" class="admin-thumb"></td>';
                            echo '<td class="admin-td-bold">' . htmlspecialchars($row['nombre']) . '</td>';
                            echo '<td class="admin-td-bold" style="color: var(--accent-purple);">$' . number_format($row['precio'], 0, ',', '.') . '</td>';
                            echo '<td><div class="admin-actions-cell">';
                            echo '<a href="editar_servicio.php?id=' . $row['id'] . '" class="admin-btn-edit"><i class="fas fa-edit"></i> Editar</a>';
                            echo '<form action="panel.php" method="POST" onsubmit="return confirm(\'¿Eliminar este servicio?\');" style="display:inline;">';
                            echo '<input type="hidden" name="accion" value="borrar_servicio">';
                            echo '<input type="hidden" name="servicio_id" value="' . $row['id'] . '">';
                            echo '<button type="submit" class="admin-btn-delete"><i class="fas fa-trash"></i> Borrar</button>';
                            echo '</form>';
                            echo '</div></td></tr>';
                        }
                    } else {
                        echo '<tr><td colspan="4" class="admin-td-empty">No tienes servicios registrados todavía.</td></tr>';
                    }
                    $stmt_serv->close();
                    ?>
                </tbody>
            </table>
        </div>

    </div>
</body>
</html>