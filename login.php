<?php
session_start();
include 'config.php';

$mensaje = "";
$claseMensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    if (empty($email) || empty($password)) {
        $mensaje = "⚠️ Todos los campos son obligatorios.";
        $claseMensaje = "error";
    } else {
        $stmt = $conn->prepare("SELECT id, username, password FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                $_SESSION['usuario'] = $row['username'];
                header("Location: dashboard.php");
                exit();
            } else {
                $mensaje = "❌ Contraseña incorrecta.";
                $claseMensaje = "error";
            }
        } else {
            $mensaje = "❌ Usuario no encontrado.";
            $claseMensaje = "error";
        }
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Login</title>
    <style>
        .mensaje {
            text-align: center;
            padding: 10px;
            margin-top: 20px;
            border-radius: 8px;
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
        <a href="login.html">Volver al login</a>
    </p>
</body>
</html>

