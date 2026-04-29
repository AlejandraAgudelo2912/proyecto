<?php
session_start();
require __DIR__ . "/../../../vendor/autoload.php";
use App\Models\ComentarioModel;
if (!isset($_SESSION["usuario"])) {
    header("Location: ../auth/login.php");
    exit;
}
$comentarioModel = new ComentarioModel();
$idComentario = $_POST['id'];
$nuevoTexto = $_POST['comentario'];
$comentarioModel->editarComentario($idComentario, $_SESSION['usuario']['id'], $nuevoTexto);
header("Location: ../libros/verLibro.php?id=" . $comentarioModel->obtenerComentarioPorId($idComentario)['id_libro']);
exit;