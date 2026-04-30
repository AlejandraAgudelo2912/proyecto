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

<div class="max-w-xl mx-auto bg-white p-6 rounded-xl shadow-md">

    <h1 class="text-2xl font-bold mb-4">Editar valoración</h1>

    <form action="editarValoracion_process.php" method="POST" class="space-y-4">

        <input type="hidden" name="id_libro" value="<?= $idLibro ?>">

        <!-- ESTRELLAS -->
        <div>
            <label class="block mb-2 font-medium">Puntuación</label>

            <div id="estrellasEditar" class="flex gap-1 text-2xl cursor-pointer">
                <?php for($i = 1; $i <= 5; $i++): ?>
                    <span data-value="<?= $i ?>">
                        <?= ($valoracion['puntuacion'] ?? 0) >= $i ? '★' : '☆' ?>
                    </span>
                <?php endfor; ?>
            </div>

            <input type="hidden" name="puntuacion" id="puntuacionEditar"
                value="<?= $valoracion['puntuacion'] ?? '' ?>">
        </div>

        <!-- COMENTARIO -->
        <div>
            <label class="block mb-2 font-medium">Comentario</label>

            <textarea name="comentario" rows="4"
                class="w-full border p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"><?= htmlspecialchars($valoracion['comentario'] ?? '') ?></textarea>
        </div>

        <!-- BOTÓN -->
        <button class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition">
            Guardar cambios
        </button>

    </form>
</div>

<!-- SCRIPT ESTRELLAS -->
<script>
const estrellas = document.querySelectorAll("#estrellasEditar span");
const input = document.getElementById("puntuacionEditar");

estrellas.forEach(estrella => {
    estrella.addEventListener("click", () => {
        const valor = estrella.getAttribute("data-value");
        input.value = valor;

        estrellas.forEach(e => {
            e.textContent = e.getAttribute("data-value") <= valor ? "★" : "☆";
        });
    });
});
</script>