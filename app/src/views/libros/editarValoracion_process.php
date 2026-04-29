<?php 
session_start();
require_once __DIR__."/../../../vendor/autoload.php";
use App\Models\ValoracionModel;

if(!isset($_SESSION["usuario"])) {
    header("Location: ../auth/login.php");
    exit;
}

$valoracionModel = new ValoracionModel();
$idLibro = $_POST['id_libro'];
$puntuacion = $_POST['puntuacion'];
$comentario = $_POST['comentario'] ?? '';
$valoracionModel->valorarLibro($idLibro, $_SESSION['usuario']['id'], $puntuacion, $comentario);
header("Location: ../libros/verLibro.php?id=$idLibro");
exit;