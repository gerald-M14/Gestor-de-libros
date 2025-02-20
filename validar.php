<?php
session_start();
require_once "persona.php";

$admin = new Administrador(1, "Juan", "Pérez", "juan@example.com", "123456", "555-1234");
$cliente = new Cliente(2, "María", "López", "maria@example.com", "abcdef", "555-5678");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = $_POST["correo"];
    $contrasena = $_POST["contrasena"];

    if ($correo == $admin->getCorreo() && $contrasena == $admin->getContrasena()) {
        $_SESSION["admin"] = $admin->getNombre();
        $_SESSION["email"] = $admin->getCorreo();
        header("Location: dashboard_admin.php");
        exit();
    }

    if ($correo == $cliente->getCorreo() && $contrasena == $cliente->getContrasena()) {
        $_SESSION["cliente"] = $cliente->getNombre();
        $_SESSION["email"] = $cliente->getCorreo();
        header("Location: dashboardCliente.php");
        exit();
    }

    header("Location: index.php?error=1");
    exit();
}
?>