<?php
session_start();
require __DIR__ . "/../../../vendor/autoload.php";

use App\Models\ComentarioModel;

if (!isset($_SESSION["usuario"])) {
    die("No autorizado");
}

$idLibro = $_POST['id_libro'] ?? null;
$texto = trim($_POST['comentario'] ?? '');

if (!$idLibro || empty($texto)) {
    die("Datos inválidos");
}

$comentarioModel = new ComentarioModel();

$comentarioModel->crearComentario(
    $idLibro,
    $_SESSION["usuario"]["id"],
    $texto
);

header("Location: ../libros/verLibro.php?id=" . $idLibro);
exit;