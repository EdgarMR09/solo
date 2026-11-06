<?php
session_start();

$mensaje = "";
$tipo_mensaje = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario'] ?? '');
    $password = $_POST['password'] ?? '';

    // 1. Validar que los campos no estén vacíos
    if (empty($usuario) || empty($password)) {
        $mensaje = "Por favor, completa todos los campos.";
        $tipo_mensaje = "error";
    } else {
        // Inicializar variable para rastrear si encontramos al usuario
        $usuario_encontrado = null;

        // 2. Buscar al usuario registrado en el arreglo de la sesión
        if (isset($_SESSION['usuarios'])) {
            foreach ($_SESSION['usuarios'] as $u) {
                if ($u['usuario'] === $usuario) {
                    $usuario_encontrado = $u;
                    break;
                }
            }
        }

        // 3. Verificar si el usuario existe y comprobar la contraseña
        if ($usuario_encontrado) {
            // 4. Utilizar password_verify() para validar la contraseña ingresada con el hash almacenado
            if (password_verify($password, $usuario_encontrado['password'])) {
                // Acceso autorizado (puedes guardar el nombre en una variable de sesión si deseas mantener la sesión activa)
                $_SESSION['usuario_logueado'] = $usuario_encontrado['nombre'];
                
                $mensaje = "¡Bienvenido al sistema, " . htmlspecialchars($usuario_encontrado['nombre']) . "!";
                $tipo_mensaje = "exito";
            } else {
                $mensaje = "Contraseña incorrecta.";
                $tipo_mensaje = "error";
            }
        } else {
            $mensaje = "El nombre de usuario no está registrado.";
            $tipo_mensaje = "error";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <!-- Vinculamos la hoja de estilos externa -->
    <link rel="stylesheet" href="style.css">
</head>
<body class="body-login">

    <div class="login-container">
        <h2>Iniciar Sesión</h2>

        <?php if (!empty($mensaje)): ?>
            <p style="color: <?php echo ($tipo_mensaje === 'exito') ? 'green' : 'red'; ?>; font-weight: bold; text-align: center;">
                <?php echo $mensaje; ?>
            </p>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <label for="usuario">Nombre de usuario (login):</label>
            <input type="text" name="usuario" id="usuario" required>

            <label for="password">Contraseña:</label>
            <input type="password" name="password" id="password" required>

            <button type="submit">Ingresar</button>
        </form>

        <a href="git.php">Ir a Registro de Usuarios</a>
    </div>

</body>
</html>