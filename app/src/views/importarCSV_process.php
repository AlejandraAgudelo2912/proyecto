<?php
session_start();
require __DIR__ . "/../../vendor/autoload.php";

use App\Models\Basedatos;

$db = new Basedatos();

if (!isset($_FILES['csv']) || $_FILES['csv']['error'] !== 0) {
    die("Error al subir archivo");
}

$file = $_FILES['csv']['tmp_name'];

$handle = fopen($file, "r");

if ($handle === false) {
    die("No se pudo leer el CSV");
}

$idUsuario = $_SESSION['usuario']['id'];

fgetcsv($handle);

while (($data = fgetcsv($handle, 1000, ",")) !== false) {

    $titulo = $data[0] ?? '';
    $autor = $data[1] ?? '';
    $genero = $data[2] ?? '';
    $anio = $data[3] ?? '';

    if (!empty($titulo) && !empty($autor)) {
        $db->crearLibro($titulo, $autor, $genero, $anio, $idUsuario, null);
    }
}

fclose($handle);

header("Location: listadoLibros.php");
exit;