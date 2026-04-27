<?php
session_start();
require __DIR__ . "/../../../vendor/autoload.php";

use App\Models\Basedatos;

if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit;
}

$db = new Basedatos();

$titulo = $_POST['titulo'] ?? '';
$autor = $_POST['autor'] ?? '';
$genero = $_POST['genero'] ?? '';
$anio = $_POST['anio'] ?? '';

$caratula = null;

if (isset($_FILES['caratula']) && $_FILES['caratula']['error'] === UPLOAD_ERR_OK) {

    $archivo = $_FILES['caratula'];

    $nombreOriginal = $archivo['name'];
    $tmp = $archivo['tmp_name'];

    $extension = pathinfo($nombreOriginal, PATHINFO_EXTENSION);
    $nombreFinal = uniqid() . "." . $extension;

    $rutaDestino = __DIR__ . "/../../../public/uploads/" . $nombreFinal;

    move_uploaded_file($tmp, $rutaDestino);

    $caratula = $nombreFinal;
}

$id_usuario = $_SESSION["usuario"]["id"];

if (empty($titulo) || empty($autor)) {
    $_SESSION['error'] = "Título y autor obligatorios";
    header("Location: crearLibro.php");
    exit;
}

$db->crearLibro($titulo, $autor, $genero, $anio, $id_usuario, $caratula);

header("Location: listadoLibros.php");
exit;