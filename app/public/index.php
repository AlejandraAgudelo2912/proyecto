<?php
require __DIR__ . "/../vendor/autoload.php";
use App\Models\Basedatos;

$basedatos = new Basedatos();

if ($basedatos->getConexion()!=null){
    require __DIR__ . "/../src/views/listadoLibros.php";
    exit;
}
else{
    $mensaje = "ERROR en la conexión a la base de datos";
    logger()->error($mensaje);
} 


?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
   
</head>
<body class="centrado">
    <?php if (isset($mensaje)): ?>
        <p style="color:red"><?= htmlspecialchars($mensaje) ?></p>
    <?php endif; ?>
</body>
</html>