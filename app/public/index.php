<?php

require __DIR__ . "/../vendor/autoload.php";
use App\Models\Basedatos;

$basedatos = new Basedatos();

if ($basedatos->getConexion()!=null){
    header ('Location: ./src/views/listadoLibros.php');
    die;
}
else{
    $mensaje = "ERROR en la conexión a al base de datos";
}


?>



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestor Incidencias</title>
    <link rel="stylesheet" href="public/css/estilos.css">
</head>
<body class="centrado">
    <h2><?= $mensaje ?></h2> 
    <div>
    <form method="POST" action="./src/controllers/crear_db.php">    
    <button id="btnCrear">Crear Base de Datos</button>
    </form>
    </div> 
</body>
</html>