<?php
session_start();
require __DIR__ . "/../../../vendor/autoload.php";

use App\Models\LibroModel;

header('Content-Type: application/json; charset=utf-8');

if (!isset($_SESSION["usuario"])) {
    echo json_encode([]);
    exit;
}

$termino = $_GET['q'] ?? '';

if (mb_strlen($termino) < 2) {
    echo json_encode([]);
    exit;
}

$libroModel = new LibroModel();
$sugerencias = $libroModel->obtenerSugerencias($termino);

echo json_encode($sugerencias);
