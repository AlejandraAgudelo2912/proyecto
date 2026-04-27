<?php
require __DIR__ . "/../app/vendor/autoload.php";

use App\Models\Basedatos;

header('Content-Type: application/json');

$db = new Basedatos();

$libros = $db->obtener_listado_Libros();

$resultado = [];

foreach ($libros as $libro) {
    $resultado[] = [
        "id" => $libro["id"],
        "titulo" => $libro["titulo"],
        "autor" => $libro["autor"],
        "disponible" => !$libro["prestado"]
    ];
}

echo json_encode($resultado);