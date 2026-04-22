<?php
session_start();
require __DIR__ . "/../../vendor/autoload.php";

use App\Models\Basedatos;

$db = new Basedatos();

$idLibro = $_GET['id'];
$idUsuario = $_SESSION['usuario']['id'];

$db->prestarLibro($idLibro, $idUsuario);

header("Location: listadoLibros.php");
exit;