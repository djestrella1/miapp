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
                // Redirige al login con parámetro para mostrar mensaje de éxito
                header("Location: login.html?registro=exito");
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
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #121212;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background: rgba(0,0,0,0.8);
            padding: 25px;
            border-radius: 12px;
            max-width: 400px;
            width: 100%;
            box-shadow: 0 4px 15px rgba(0,0,0,0.6);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #fff;
        }
        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
        }
        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 16px;
            border-radius: 6px;
            border: none;
            font-size: 16px;
        }
        input[type="text"]:focus, input[type="email"]:focus, input[type="password"]:focus {
            outline: 2px solid #4CAF50;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #4CAF50;
            border: none;
            color: white;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #45a049;
        }
        p {
            text-align: center;
            margin-top: 12px;
            color: #ddd;
        }
        a {
            color: #90ee90;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if (!empty($mensaje)): ?>
            <div class="mensaje <?= $claseMensaje; ?>"><?= $mensaje; ?></div>
        <?php endif; ?>

        <h2>Registro</h2>
        <form action="register.php" method="POST" novalidate>
            <label>Nombre de usuario</label>
            <input type="text" name="username" required />

            <label>Correo electrónico</label>
            <input type="email" name="email" required />

            <label>Contraseña</label>
            <input type="password" name="password" required />

            <button type="submit">Registrarse</button>
        </form>
        <p>
            ¿Ya tienes cuenta? <a href="login.html">Inicia sesión</a>
        </p>
    </div>
</body>
</html>

