<?php 
session_start();
require_once __DIR__."/../../../vendor/autoload.php";

use App\Models\ValoracionModel;

if(!isset($_SESSION["usuario"])) {
    header("Location: ../auth/login.php");
    exit;
}

$valoracionModel = new ValoracionModel();
$idLibro = $_GET['id'];
$valoracion = $valoracionModel->obtenerValoraciones($idLibro);
?>
<?php include __DIR__ . "/../layout.php"; ?>

<div class="max-w-xl mx-auto bg-white p-6 rounded-xl shadow">

    <h1 class="text-2xl font-bold mb-4">Editar valoración</h1>

    <form action="editarValoracion_process.php" method="POST">

        <input type="hidden" name="id_libro" value="<?= $idLibro ?>">

        <label class="block mb-2 font-medium">Puntuación (1-5)</label>
        <input type="number" name="puntuacion" min="1" max="5"
            value="<?= htmlspecialchars($valoracion['puntuacion'] ?? '') ?>"
            class="w-full border p-2 mb-3 rounded">

        <label class="block mb-2 font-medium">Comentario</label>
        <textarea name="comentario" rows="4"
            class="w-full border p-2 mb-3 rounded"><?= htmlspecialchars($valoracion['comentario'] ?? '') ?></textarea>

        <button class="bg-blue-600 text-white px-4 py-2 rounded">
            Guardar valoración
        </button>

    </form>
</div>
