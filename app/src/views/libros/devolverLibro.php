<?php
session_start();
require __DIR__ . "/../../../vendor/autoload.php";

use App\Models\PrestamosModel;

$prestamoModel = new PrestamosModel();
$idLibro = $_GET['id'];
$idUsuario = $_SESSION['usuario']['id'];

$prestamoModel->devolverLibro($idLibro, $idUsuario);
header("Location: listadoLibros.php");
exit;