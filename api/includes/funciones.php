<?php

require_once __DIR__ . "/../../app/vendor/autoload.php";

function generarApiKey() {
    return bin2hex(random_bytes(32));
}

function obtener_usuario_por_key($apiKey) {

    if (!$apiKey) return false;

    $db = (new App\Models\Basedatos())->getConexion();

    $stmt = $db->prepare("SELECT id, rol FROM usuarios WHERE api_key = ?");
    $stmt->execute([$apiKey]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}