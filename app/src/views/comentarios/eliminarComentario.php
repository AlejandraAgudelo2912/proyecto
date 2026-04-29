<?php
session_start();
require __DIR__ . "/../../../vendor/autoload.php";
use App\Models\ComentarioModel;
if (!isset($_SESSION["usuario"])) {
    header("Location: ../auth/login.php");
    exit;
}
$comentarioModel = new ComentarioModel();
$idComentario = $_GET['id'];
$idLibro = $_GET['id_libro'];

$comentarioModel->eliminarComentario($idComentario, $_SESSION['usuario']['id']);

header("Location: ../libros/verLibro.php?id=" . $idLibro);
exit;



