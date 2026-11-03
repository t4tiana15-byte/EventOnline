<?php
session_start();
include '../php/conexion.php';

if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: ../templates/login.php?error=Acceso no autorizado");
    exit();
}

$usuario_actual = $_SESSION['usuario'];
$stmt_user = $conexion->prepare("SELECT id FROM usuarios WHERE usuario = ?");
$stmt_user->bind_param("s", $usuario_actual);
$stmt_user->execute();
$res_user = $stmt_user->get_result();
$user_data = $res_user->fetch_assoc();
$my_user_id = $user_data['id'];
$stmt_user->close();

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$mensaje = "";

$stmt = $conexion->prepare("SELECT * FROM servicios WHERE id = ? AND usuario_id = ?");
$stmt->bind_param("ii", $id, $my_user_id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    header("Location: panel.php?error=No autorizado");
    exit();
}

$servicio = $resultado->fetch_assoc();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = trim($_POST['nombre']);
    $descripcion = trim($_POST['descripcion']);
    $precio = floatval($_POST['precio']);
    $imagen_actual = $_POST['imagen_actual'];
    $imagen_ruta = $imagen_actual;

    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $nombre_archivo = 'serv_' . time() . '_' . basename($_FILES['imagen']['name']);
        $ruta_destino = '../static/img/' . $nombre_archivo;
        
        if (move_uploaded_file($_FILES['imagen']['tmp_name'], $ruta_destino)) {
            $imagen_ruta = '../static/img/' . $nombre_archivo;
        }
    }

    $stmt_up = $conexion->prepare("UPDATE servicios SET nombre = ?, descripcion = ?, imagen = ?, precio = ? WHERE id = ? AND usuario_id = ?");
    $stmt_up->bind_param("sssDii", $nombre, $descripcion, $imagen_ruta, $precio, $id, $my_user_id);

    if ($stmt_up->execute()) {
        header("Location: panel.php?exito=ServicioActualizado");
        exit();
    } else {
        $mensaje = "Error al actualizar el servicio.";
    }
    $stmt_up->close();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Servicio - EventOnline</title>
    <link rel="stylesheet" href="../static/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="admin-body">
    <div class="admin-container admin-container-wide">
        <h2 class="admin-title"><i class="fas fa-edit"></i> Editar Servicio</h2>
        
        <?php if (!empty($mensaje)): ?>
            <div class="admin-alert"><?php echo htmlspecialchars($mensaje); ?></div>
        <?php endif; ?>

        <form action="editar_servicio.php?id=<?php echo $id; ?>" method="POST" enctype="multipart/form-data" class="formulario admin-form-box">
            <input type="hidden" name="imagen_actual" value="<?php echo htmlspecialchars($servicio['imagen']); ?>">
            
            <div id="grupo-campo">
                <label>Nombre del Servicio</label>
                <input type="text" name="nombre" value="<?php echo htmlspecialchars($servicio['nombre']); ?>" required>
            </div>
            
            <div id="grupo-campo">
                <label>Descripción</label>
                <input type="text" name="descripcion" value="<?php echo htmlspecialchars($servicio['descripcion']); ?>" required>
            </div>
            
            <div id="grupo-campo">
                <label>Precio ($)</label>
                <input type="number" name="precio" step="1000" value="<?php echo htmlspecialchars($servicio['precio']); ?>" required>
            </div>
            
            <div id="grupo-campo">
                <label>Imagen Actual</label><br>
                <img src="<?php echo htmlspecialchars($servicio['imagen']); ?>" class="admin-thumb-preview"><br><br>
                <label>Cambiar Imagen (Opcional)</label>
                <input type="file" name="imagen" accept="image/*" class="admin-file-input">
            </div>
            
            <button type="submit" id="boton">Guardar Cambios</button>
        </form>

        <p style="margin-top: 15px; text-align: center;">
            <a href="panel.php" class="forgot-password">Regresar al Panel</a>
        </p>
    </div>
</body>
</html>