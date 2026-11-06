<?php
session_start();

// Inicializar el arreglo de usuarios en la sesión si no existe
if (!isset($_SESSION['usuarios'])) {
    $_SESSION['usuarios'] = [];
}

$mensaje = "";
$tipo_mensaje = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $usuario = trim($_POST['usuario'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmar_password = $_POST['confirmar_password'] ?? '';

    // 1. Validar que todos los campos son obligatorios
    if (empty($nombre) || empty($usuario) || empty($password) || empty($confirmar_password)) {
        $mensaje = "Todos los campos son obligatorios.";
        $tipo_mensaje = "error";
    } 
    // 2. Validar que la contraseña tenga al menos 6 caracteres
    elseif (strlen($password) < 6) {
        $mensaje = "La contraseña debe tener al menos 6 caracteres.";
        $tipo_mensaje = "error";
    } 
    // 3. Validar que la contraseña y su confirmación coincidan
    elseif ($password !== $confirmar_password) {
        $mensaje = "Las contraseñas no coinciden.";
        $tipo_mensaje = "error";
    } else {
        // Verificar si el usuario ya existe
        $existe = false;
        foreach ($_SESSION['usuarios'] as $u) {
            if ($u['usuario'] === $usuario) {
                $existe = true;
                break;
            }
        }

        if ($existe) {
            $mensaje = "El nombre de usuario ya está registrado.";
            $tipo_mensaje = "error";
        } else {
            // 4. Convertir la contraseña en hash utilizando password_hash()
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            // Almacenar en el arreglo de la sesión
            $_SESSION['usuarios'][] = [
                'nombre' => $nombre,
                'usuario' => $usuario,
                'password' => $passwordHash
            ];

            $mensaje = "¡Usuario registrado correctamente!";
            $tipo_mensaje = "exito";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuarios</title>
</head>
<body>
    <h2>Registro de Usuarios</h2>

    <?php if (!empty($mensaje)): ?>
        <p style="color: <?php echo ($tipo_mensaje === 'exito') ? 'green' : 'red'; ?>; font-weight: bold;">
            <?php echo $mensaje; ?>
        </p>
    <?php endif; ?>

    <form action="registro.php" method="POST">
        <label for="nombre">Nombre:</label><br>
        <input type="text" name="nombre" id="nombre" required><br><br>

        <label for="usuario">Nombre de usuario (login):</label><br>
        <input type="text" name="usuario" id="usuario" required><br><br>

        <label for="password">Contraseña:</label><br>
        <input type="password" name="password" id="password" required><br><br>

        <label for="confirmar_password">Confirmar Contraseña:</label><br>
        <input type="password" name="confirmar_password" id="confirmar_password" required><br><br>

        <button type="submit">Registrarse</button>
    </form>

    <br>
    <a href="login.php">Ir a Iniciar Sesión</a>
</body>
</html>