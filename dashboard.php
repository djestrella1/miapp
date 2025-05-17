<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.html");
    exit();
}

$usuario = $_SESSION['usuario'];

date_default_timezone_set('America/Lima');
$hora = date("H");
if ($hora < 12) {
    $saludo = "Buenos días";
} elseif ($hora < 19) {
    $saludo = "Buenas tardes";
} else {
    $saludo = "Buenas noches";
}

$mensajes = [
    "Recuerda que cada día es una nueva oportunidad para brillar ",
    "Sigue así, estás haciendo un gran trabajo! ",
    "La perseverancia es la clave del éxito ",
    "No olvides sonreír, hoy será un gran día! "
];
$mensaje_aleatorio = $mensajes[array_rand($mensajes)];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Dashboard</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            /* Imagen de fondo */
            background: url('imagen/imagen1.jpeg') no-repeat center center fixed;
            background-size: cover;
            color: white;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            text-shadow: 0 0 5px black; /* Para que el texto resalte sobre la imagen */
        }
        .container {
            text-align: center;
            background: rgba(0, 0, 0, 0.5); /* Fondo semitransparente para mejor lectura */
            padding: 40px 60px;
            border-radius: 15px;
            box-shadow: 0 0 20px #000dff;
        }
        h1 {
            margin-bottom: 10px;
            font-size: 3rem;
        }
        p {
            font-size: 1.3rem;
            margin-bottom: 20px;
        }
        .mensaje-motivacional {
            font-style: italic;
            margin-top: 20px;
            font-size: 1.1rem;
            color: #cde1ff;
        }
        .logout-button {
            background: #ff3b3b;
            border: none;
            padding: 12px 25px;
            font-size: 1rem;
            border-radius: 8px;
            color: white;
            cursor: pointer;
            transition: background 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }
        .logout-button:hover {
            background: #ff1a1a;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><?= $saludo ?>, <?= htmlspecialchars($usuario) ?>!</h1>
        <p>Bienvenido a tu dashboard.</p>
        <p class="mensaje-motivacional"><?= $mensaje_aleatorio ?></p>
        <a href="logout.php" class="logout-button">Cerrar sesión</a>
    </div>
</body>
</html>

