<?php
session_start();
require_once "persona.php";

$admin = new Administrador(1, "Juan", "Pérez", "juan@example.com", "123456", "555-1234");
$Cliente = new Cliente(2, "María", "López", "maria@example.com", "abcdef", "555-5678");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = $_POST["correo"];
    $contrasena = $_POST["contrasena"];

    if ($correo == $admin->getCorreo() && $contrasena == $admin->getContrasena()) {
        $_SESSION["admin"] = $admin->getNombre();
        header("Location: dashboard_admin.php");
        exit();
    }

    if ($correo == $Cliente->getCorreo() && $contrasena == $Cliente->getContrasena()) {
        $_SESSION["Cliente"] = $Cliente->getNombre();
        header("Location: dashboardCliente.php");
        exit();
    }

    echo "<script>alert('Correo o contraseña incorrectos'); window.location.href='index.php';</script>";
}
?>
