<?php
session_start();
require __DIR__ . "/../../vendor/autoload.php";

use App\Models\Basedatos;

$db = new Basedatos();
$pdo = $db->getConexion();

$idUsuario = $_SESSION['usuario']['id'];
$tipo = $_POST['tipo'] ?? 'csv';


//CASO CSV
if ($tipo === 'csv') {

    if (!isset($_FILES['csv']) || $_FILES['csv']['error'] !== 0) {
        die("Error al subir CSV");
    }

    $handle = fopen($_FILES['csv']['tmp_name'], "r");

    fgetcsv($handle);

    while (($data = fgetcsv($handle, 1000, ",")) !== false) {

        $titulo = $data[0] ?? '';
        $autor = $data[1] ?? '';
        $genero = $data[2] ?? '';
        $anio = $data[3] ?? '';

        if ($titulo && $autor) {
            $db->crearLibro($titulo, $autor, $genero, $anio, $idUsuario, null);
        }
    }

    fclose($handle);
}

// CASO ZIP
if ($tipo === 'zip') {

    if (!isset($_FILES['zip']) || $_FILES['zip']['error'] !== 0) {
        die("Error al subir ZIP");
    }

    $tmpZip = $_FILES['zip']['tmp_name'];

    $extractPath = __DIR__ . "/../../storage/tmp_" . time();
    mkdir($extractPath, 0777, true);

    $zip = new ZipArchive;

    if ($zip->open($tmpZip) === TRUE) {
        $zip->extractTo($extractPath);
        $zip->close();
    } else {
        die("No se pudo abrir ZIP");
    }

    //funcion para buscar el archivo
    function buscarArchivo($dir, $nombre) {
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($dir)
        );

        foreach ($files as $file) {
            if ($file->getFilename() === $nombre) {
                return $file->getPathname();
            }
        }

        return null;
    }

    $csvPath = buscarArchivo($extractPath , "libros.csv");

    if (!$csvPath) {
        die("No hay libros.csv en el ZIP");
    }

    $handle = fopen($csvPath, "r");

    fgetcsv($handle);

    while (($data = fgetcsv($handle, 1000, ",")) !== false) {

        $titulo = $data[0] ?? '';
        $autor = $data[1] ?? '';
        $genero = $data[2] ?? '';
        $anio = $data[3] ?? '';
        $caratula = $data[4] ?? null;

        $nombreFinal = null;

        if ($caratula) {
            $rutaImagen = buscarArchivo($extractPath, $caratula);;

            if (file_exists($rutaImagen)) {
                $ext = pathinfo($caratula, PATHINFO_EXTENSION);
                $nombreFinal = uniqid() . "." . $ext;

                copy(
                    $rutaImagen,
                    __DIR__ . "/../../public/uploads/" . $nombreFinal
                );
            }
        }

        if ($titulo && $autor) {
            $db->crearLibro($titulo, $autor, $genero, $anio, $idUsuario, $nombreFinal);
        }
    }

    fclose($handle);
}

header("Location: listadoLibros.php");
exit;