<?php
session_start();
require __DIR__ . "/../../../vendor/autoload.php";

use App\Models\UsuarioModel;

if (!isset($_SESSION["usuario"]) || $_SESSION["usuario"]["rol"] !== 'admin') {
    $_SESSION['error'] = "Acceso denegado";
    header("Location: ../index.php");
    exit;
}

$id = $_GET['id'] ?? null;

if (!$id) {
    $_SESSION['error'] = "ID de usuario no proporcionado";
    header("Location: ../admin/admin.php");
    exit;
}

if ($id == $_SESSION["usuario"]["id"]) {
    $_SESSION['error'] = "No puedes eliminar tu propia cuenta";
    header("Location: ../admin/admin.php");
    exit;
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