<?php
session_start();
require __DIR__ . "/../../../vendor/autoload.php";

use App\Models\PrestamosModel;

$prestamosModel = new PrestamosModel();

$idLibro = $_GET['id'];
$idUsuario = $_SESSION['usuario']['id'];

$prestamosModel->prestarLibro($idLibro, $idUsuario);

header("Location: listadoLibros.php");
exit;