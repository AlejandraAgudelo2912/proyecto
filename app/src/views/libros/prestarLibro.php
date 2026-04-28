<?php
session_start();
require __DIR__ . "/../../../vendor/autoload.php";

use App\Models\PrestamosModel;

if (!isset($_SESSION['usuario'])) {
    header("Location: ../auth/login.php");
    exit;
}

$prestamosModel = new PrestamosModel();

$idLibro = $_GET['id'] ?? null;
$idUsuario = $_SESSION['usuario']['id'];

if ($idLibro) {

    $ok = $prestamosModel->solicitarPrestamo($idLibro, $idUsuario);

    // 🔥 opcional: puedes meter mensajes por sesión
    if ($ok) {
        $_SESSION['mensaje'] = "Solicitud enviada 🔥";
    } else {
        $_SESSION['error'] = "No se pudo solicitar el préstamo 😅";
    }
}

header("Location: verLibro.php?id=" . $idLibro);
exit;