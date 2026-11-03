<?php
session_start();
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = trim($_POST['usuario']);
    $contrasena = $_POST['contrasena'];

    if (empty($usuario) || empty($contrasena)) {
        header("Location: ../templates/login.php?error=Completa todos los campos");
        exit();
    }

    $stmt = $conexion->prepare("SELECT id, usuario, email, contrasena, rol FROM usuarios WHERE usuario = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $user = $resultado->fetch_assoc();
        if (password_verify($contrasena, $user['contrasena'])) {
            $_SESSION['usuario'] = $user['usuario'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['rol'] = $user['rol'];

            header("Location: ../templates/index.php");
            exit();
        }
    }

    header("Location: ../templates/login.php?error=Usuario o contraseña incorrectos");
    exit();
    
    $stmt->close();
    $conexion->close();
}
?>