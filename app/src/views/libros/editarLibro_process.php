<?php
session_start();
require __DIR__ . "/../../../vendor/autoload.php";

use App\Models\LibroModel;

if (!isset($_SESSION["usuario"])) {
    header("Location: ../auth/login.php");
    exit;
}

$libroModel = new LibroModel();

$id = $_POST['id'];
$titulo = $_POST['titulo'];
$autor = $_POST['autor'];
$genero = $_POST['genero'];
$anio = $_POST['anio'];

$libro = $libroModel->obtenerLibroPorId($id);

if ($libro['id_usuario'] != $_SESSION['usuario']['id']) {
    die("No tienes permisos 💀");
}

$libroModel->actualizarLibro($id, $titulo, $autor, $genero, $anio);

header("Location: verLibro.php?id=" . $id);
exit;