<?php
session_start();
require __DIR__ . "/../vendor/autoload.php";
use App\Models\Basedatos;

$basedatos = new Basedatos();

if ($basedatos->getConexion()!=null){
    if (!isset($_SESSION["usuario"])){
        header("Location: ../src/views/login.php");
        die;
    }else{
        header("Location: ../src/views/listadoLibros.php");
        logger()->info("El usuario: ".$_SESSION["usuario"]["nombre"]." ha iniciado sesión.");
        die;
    }
}
else{
    $mensaje = "ERROR en la conexión a la base de datos";
    logger()->error($mensaje);
} 
?>
