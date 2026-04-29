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

$resultado = $libroModel->eliminarLibro($_GET['id']);

if (!$resultado) {
    $_SESSION['error'] = "No puedes borrar este libro porque tiene préstamos asociados";
} else {
    $_SESSION['error'] = "Libro eliminado correctamente";
}

header("Location: ../admin/admin.php");
exit;