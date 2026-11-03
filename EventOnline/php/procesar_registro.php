<?php
session_start();
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = trim($_POST['usuario']);
    $email = trim($_POST['email']);
    $contrasena = $_POST['contrasena'];
    $rol = isset($_POST['rol']) ? trim($_POST['rol']) : 'user';

    if (!in_array($rol, ['user', 'admin'])) {
        $rol = 'user';
    }

    if (empty($usuario) || empty($email) || empty($contrasena)) {
        header("Location: ../templates/register.php?error=Todos los campos son obligatorios");
        exit();
    }

    $hashed_password = password_hash($contrasena, PASSWORD_DEFAULT);

    $stmt = $conexion->prepare("INSERT INTO usuarios (usuario, email, contrasena, rol) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $usuario, $email, $hashed_password, $rol);

    if ($stmt->execute()) {
        $_SESSION['usuario'] = $usuario;
        $_SESSION['email'] = $email;
        $_SESSION['rol'] = $rol;
        header("Location: ../templates/index.php");
        exit();
    } else {
        header("Location: ../templates/register.php?error=El usuario o correo ya están registrados");
        exit();
    }

    $stmt->close();
    $conexion->close();
}
?>