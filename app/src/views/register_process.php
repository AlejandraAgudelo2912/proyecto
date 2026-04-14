<?php
session_start();
require __DIR__ . "/../../vendor/autoload.php";

use App\Models\Basedatos;

session_start();
$db = new Basedatos();

$nombre = $_POST['nombre'] ?? '';
$apellido1 = $_POST['apellido1'] ?? '';
$apellido2 = $_POST['apellido2'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$passwordConfirm = $_POST['password_confirm'] ?? '';

if (empty($nombre) || empty($email) || empty($password) || empty($apellido1)) {
    $_SESSION['error'] = "Rellene los campos son obligatorios";
    $_SESSION['old_nombre'] = $nombre;
    $_SESSION['old_apellido1'] = $apellido1;
    $_SESSION['old_apellido2'] = $apellido2;
    $_SESSION['old_email'] = $email;
    header("Location: register.php");
    exit;
}

if ($password !== $passwordConfirm) {
    $_SESSION['error'] = "Las contraseñas no coinciden";
    $_SESSION['old_nombre'] = $nombre;
    $_SESSION['old_apellido1'] = $apellido1;
    $_SESSION['old_apellido2'] = $apellido2;
    $_SESSION['old_email'] = $email;
    header("Location: register.php");
    exit;
}

if ($db->existeUsuario($email)) {
    $_SESSION['error'] = "El email ya está registrado";
    $_SESSION['old_nombre'] = $nombre;
    $_SESSION['old_apellido1'] = $apellido1;
    $_SESSION['old_apellido2'] = $apellido2;
    $_SESSION['old_email'] = $email;
    header("Location: register.php");
    exit;
}

$passwordHash = password_hash($password, PASSWORD_DEFAULT);

$ok = $db->crearUsuario($nombre, $apellido1, $apellido2, $email, $passwordHash);

if (!$ok) {
    $_SESSION['error'] = "Error al registrar usuario";
    $_SESSION['old_nombre'] = $nombre;
    $_SESSION['old_apellido1'] = $apellido1;
    $_SESSION['old_apellido2'] = $apellido2;
    $_SESSION['old_email'] = $email;
    logger()->error("Error al registrar usuario: " . $email);
    header("Location: register.php");
    exit;
}
session_destroy();
logger()->info("Nuevo usuario registrado: " . $email);
header("Location: ../../public/index.php");
exit;