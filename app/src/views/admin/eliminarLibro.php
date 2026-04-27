<?php
session_start();
require __DIR__ . "/../../../vendor/autoload.php";

use App\Models\LibroModel;

if (!isset($_SESSION["usuario"]) || $_SESSION["usuario"]["rol"] !== 'admin') {
    die("Acceso denegado");
}

$id = $_GET['id'] ?? null;

if (!$id) {
    die("ID no válido");
}

$libroModel = new LibroModel();

$libroModel->eliminarLibro($id);

header("Location: ../admin/admin.php");
exit;