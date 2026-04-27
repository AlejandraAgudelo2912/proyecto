<?php
session_start();
require __DIR__ . "/../../../vendor/autoload.php";

use App\Models\UsuarioModel;

if (!isset($_SESSION["usuario"]) || $_SESSION["usuario"]["rol"] !== 'admin') {
    die("Acceso denegado 💀");
}

$id = $_POST['id'] ?? null;
$rol = $_POST['rol'] ?? null;

if (!$id || !$rol) {
    die("Datos inválidos");
}

if ($id == $_SESSION["usuario"]["id"] && $rol !== 'admin') {
    die("No puedes quitarte el admin");
}

$usuarioModel = new UsuarioModel();

$usuarioModel->cambiarRol($id, $rol);

header("Location: ../admin/admin.php");
exit;