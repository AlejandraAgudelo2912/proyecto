<?php
require __DIR__ . "/../../../vendor/autoload.php";

use App\Models\UsuarioModel;

session_start();
$usuarioModel = new UsuarioModel();

$email = $_POST["email"] ?? "";
$password = $_POST["password"] ?? "";

if (empty($email) || empty($password)) {
    $_SESSION["error"] = "Por favor, completa todos los campos.";
    $_SESSION["old_email"] = $email;
    header("Location: login.php");
    exit();
}

$usuario = $usuarioModel->obtener_usuario_por_email($email);
if (!$usuario || !password_verify($password, $usuario["password"])) {
    $_SESSION["error"] = "Correo electrónico o contraseña incorrectos.";
    $_SESSION["old_email"] = $email;
    header("Location: login.php");
    exit();
}

$_SESSION["usuario"]["id"] = $usuario["id"];
$_SESSION["usuario"]["nombre"] = $usuario["nombre"];

logger()->info("Usuario ha iniciado sesión: " . $usuario["email"]);

header("Location: ../../public/index.php");
exit();

