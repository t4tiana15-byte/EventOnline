<?php
session_start();
include 'conexion.php';
header('Content-Type: application/json');

$accion = isset($_GET['accion']) ? $_GET['accion'] : (isset($_POST['accion']) ? $_POST['accion'] : '');

// 1. Verificar si ya existe reserva previa para la misma fecha y servicio/sitio
if ($accion === 'verificar') {
    $nombre = trim($_GET['nombre']);
    $fecha = trim($_GET['fecha']);

    $stmt = $conexion->prepare("SELECT id FROM reservas WHERE item_nombre = ? AND fecha = ?");
    $stmt->bind_param("ss", $nombre, $fecha);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        echo json_encode(['disponible' => false, 'mensaje' => "El espacio o servicio '$nombre' ya está reservado para la fecha $fecha."]);
    } else {
        echo json_encode(['disponible' => true]);
    }
    $stmt->close();
    exit();
}

// 2. Guardar la reserva con el método de pago seleccionado
if ($accion === 'guardar') {
    if (!isset($_SESSION['usuario'])) {
        echo json_encode(['exito' => false, 'mensaje' => "Debes iniciar sesión para completar la reserva."]);
        exit();
    }

    $usuario_actual = $_SESSION['usuario'];
    $stmt_u = $conexion->prepare("SELECT id FROM usuarios WHERE usuario = ?");
    $stmt_u->bind_param("s", $usuario_actual);
    $stmt_u->execute();
    $my_id = $stmt_u->get_result()->fetch_assoc()['id'];
    $stmt_u->close();

    $input = json_decode(file_get_contents('php://input'), true);
    $items = isset($input['items']) ? $input['items'] : [];
    $metodo_pago = isset($input['metodo_pago']) ? trim($input['metodo_pago']) : 'Efectivo';

    foreach ($items as $item) {
        $nombre = trim($item['nombre']);
        $fecha = trim($item['fecha']);
        $hora = trim($item['hora']);

        $stmt = $conexion->prepare("INSERT INTO reservas (usuario_id, item_nombre, fecha, hora, metodo_pago) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issss", $my_id, $nombre, $fecha, $hora, $metodo_pago);
        if (!$stmt->execute()) {
            echo json_encode(['exito' => false, 'mensaje' => "El servicio o sitio '$nombre' ya fue reservado para la fecha $fecha."]);
            $stmt->close();
            exit();
        }
        $stmt->close();
    }

    echo json_encode(['exito' => true]);
    exit();
}
?>