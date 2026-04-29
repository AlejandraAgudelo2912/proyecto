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

$nombreImagen = $libro['caratula'];

if (!empty($_FILES['caratula']['name'])) {

    $archivo = $_FILES['caratula'];

    $nombreImagen = time() . "_" . $archivo['name'];

    $rutaDestino = __DIR__ . "/../../../public/uploads/" . $nombreImagen;

    move_uploaded_file($archivo['tmp_name'], $rutaDestino);
}

$libroModel->actualizarLibroConImagen(
    $id,
    $titulo,
    $autor,
    $genero,
    $anio,
    $nombreImagen
);

header("Location: verLibro.php?id=" . $id);
exit;