<?php
session_start();
require_once "persona.php";


$admin = new Administrador(1, "Juan", "Pérez", "juan@example.com", "123456", "555-1234");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = $_POST["correo"];
    $contrasena = $_POST["contrasena"];

    // Validación de credenciales
    if ($correo == $admin->getCorreo() && $contrasena == $admin->getContrasena()) {
        $_SESSION["admin"] = $admin->getNombre();
        header("Location: dashboard.php");
        exit();
    } else {
        echo "<script>alert('Correo o contraseña incorrectos'); window.location.href='index.php';</script>";
    }
}
?>
