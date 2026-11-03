<?php
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = trim($_POST['token']);
    $email = trim($_POST['email']);
    $nueva_contrasena = trim($_POST['nueva_contrasena']);

    if (!empty($token) && !empty($email) && !empty($nueva_contrasena)) {
        // Validar token y correo
        $stmt = $conexion->prepare("SELECT * FROM password_resets WHERE token = ? AND email = ?");
        $stmt->bind_param("ss", $token, $email);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows > 0) {
            $password_hash = password_hash($nueva_contrasena, PASSWORD_DEFAULT);

            // Actualizar la contraseña del usuario
            $stmt_up = $conexion->prepare("UPDATE usuarios SET contrasena = ? WHERE email = ?");
            $stmt_up->bind_param("ss", $password_hash, $email);
            
            if ($stmt_up->execute()) {
                // Eliminar el token ya utilizado
                $stmt_del = $conexion->prepare("DELETE FROM password_resets WHERE email = ?");
                $stmt_del->bind_param("s", $email);
                $stmt_del->execute();
                $stmt_del->close();

                header("Location: ../templates/login.php?exito=Contraseña actualizada con éxito. Inicia sesión.");
                exit();
            } else {
                header("Location: ../templates/restablecer_contrasena.php?token=$token&error=Error al actualizar la contraseña.");
                exit();
            }
            $stmt_up->close();
        } else {
            header("Location: ../templates/recuperar_contrasena.php?error=Token inválido o expirado.");
            exit();
        }
        $stmt->close();
    }
}
?>