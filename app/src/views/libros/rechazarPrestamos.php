<?php 
session_start();
require __DIR__ . "/../../../vendor/autoload.php";

use App\Models\PrestamosModel;

$model = new PrestamosModel();

$model->rechazarPrestamo($_GET['id']);

header("Location: solicitudesPrestamos.php");
exit;