<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Iniciar Sesión</title>
</head>
<body>

<div class="login-container">
    <h2>INICIAR SESIÓN</h2>
    <?php
    if (isset($_GET['error']) && $_GET['error'] == 1) {
        echo '<p class="error-message">Correo o contraseña incorrectos</p>';
    }
    ?>
    <form class="login-form" action="validar.php" method="POST">
        <input class="input-email" type="email" name="correo" placeholder="Correo" required>
        <input class="input-password" type="password" name="contrasena" placeholder="Contraseña" required>
        <button class="login-button" type="submit">Iniciar Sesión</button>
    </form>
</div>

</body>
</html>