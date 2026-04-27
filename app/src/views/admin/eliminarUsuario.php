<?php
session_start();
require __DIR__ . "/../../../vendor/autoload.php";

use App\Models\Basedatos;

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

$db = new Basedatos();

$db->eliminarUsuario($id);

header("Location: ../admin/admin.php");
exit;