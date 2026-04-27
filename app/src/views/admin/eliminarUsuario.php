<?php
session_start();
require __DIR__ . "/../../../vendor/autoload.php";

use App\Models\UsuarioModel;

if (!isset($_SESSION["usuario"]) || $_SESSION["usuario"]["rol"] !== 'admin') {
    die("Acceso denegado 💀");
}

$id = $_GET['id'] ?? null;

if (!$id) {
    die("ID no válido");
}

if ($id == $_SESSION["usuario"]["id"]) {
    die("No puedes eliminarte a ti mismo");
}

$usuarioModel = new UsuarioModel();

if($usuarioModel->usuarioTienePrestamos($id)) {
    $_SESSION['error'] = "Este usuario tiene préstamos activos";
    header("Location: ../admin/admin.php");
    exit;
}

$usuarioModel->eliminarUsuario($id);

header("Location: ../admin/admin.php");
exit;