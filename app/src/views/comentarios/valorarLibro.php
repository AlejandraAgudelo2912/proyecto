<?php
session_start();
require __DIR__ . "/../../../vendor/autoload.php";

use App\Models\ValoracionModel;

if (!isset($_SESSION["usuario"])) {
    die("No autorizado");
}

$idLibro = $_POST['id_libro'];
$puntuacion = $_POST['puntuacion'];
$comentario = trim($_POST['comentario'] ?? '');

$valoracionModel = new ValoracionModel();

$valoracionModel->valorarLibro($idLibro, $_SESSION["usuario"]["id"], $puntuacion, $comentario);