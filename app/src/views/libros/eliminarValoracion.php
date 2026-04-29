<?php
session_start();
require __DIR__ . "/../../../vendor/autoload.php";

use App\Models\ValoracionModel;

if (!isset($_SESSION['usuario'])) {
    header("Location: " . BASE_URL . "src/views/auth/login.php");
    exit();
}

$valoracionModel = new ValoracionModel();

$idLibro = $_GET['id'] ?? null;
if (!$idLibro) {
    header("Location: " . BASE_URL);
    exit();
}

$valoracionModel->eliminarValoracion($idLibro, $_SESSION['usuario']['id']);
header("Location: verLibro.php?id=" . $idLibro);
exit();