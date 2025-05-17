<?php
session_start();
include 'config.php';

$mensaje = "";
$claseMensaje = "";

// Función para validar el email con filtro estándar
function esEmailValido($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    if (empty($username) || empty($email) || empty($password)) {
        $mensaje = "⚠️ Todos los campos son obligatorios.";
        $claseMensaje = "error";
    } elseif (!esEmailValido($email)) {
        $mensaje = "⚠️ El correo electrónico no es válido, inténtelo de nuevo.";
        $claseMensaje = "error";
    } else {
        $verificar = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
        $verificar->bind_param("s", $email);
        $verificar->execute();
        $verificar->store_result();

        if ($verificar->num_rows > 0) {
            $mensaje = "⚠️ Este correo ya está registrado.";
            $claseMensaje = "error";
        } else {
            $pass_hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO usuarios (username, password, email) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $pass_hashed, $email);

            if ($stmt->execute()) {
                $_SESSION['usuario'] = $username;
                header("Location: dashboard.php");
                exit();
            } else {
                $mensaje = "❌ Error al registrar. Inténtalo de nuevo.";
                $claseMensaje = "error";
            }
            $stmt->close();
        }
        $verificar->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Registro</title>
    <style>
        .mensaje {
            text-align: center;
            padding: 10px;
            margin-top: 20px;
            border-radius: 8px;
        }
        .exito {
            background-color: #d4edda;
            color: #155724;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <?php if (!empty($mensaje)): ?>
        <div class="mensaje <?= $claseMensaje; ?>"><?= $mensaje; ?></div>
    <?php endif; ?>
    <p style="text-align:center; margin-top: 20px;">
        <a href="registro.html">Volver al registro</a>
    </p>
</body>
</html>

