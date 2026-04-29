<?php
session_start();
require __DIR__ . "/../../../vendor/autoload.php";

use App\Models\ComentarioModel;

if (!isset($_SESSION["usuario"])) {
    header("Location: ../auth/login.php");
    exit;
}

$comentarioModel = new ComentarioModel();
$id = $_GET['id'];
$comentario = $comentarioModel->obtenerComentarioPorId($id);
// seguridad básica
if ($comentario['id_usuario'] != $_SESSION['usuario']['id']) {
    die("No tienes permisos 💀");
}
?>
<?php include __DIR__ . "/../layout.php"; ?>
<div class="max-w-xl mx-auto bg-white p-6 rounded-xl shadow">

    <h1 class="text-2xl font-bold mb-4">Editar comentario</h1>

    <form action="editarComentario_process.php" method="POST">

        <input type="hidden" name="id" value="<?= $comentario['id'] ?>">

        <textarea name="comentario" rows="5"
            class="w-full border p-2 mb-3 rounded"><?= htmlspecialchars($comentario['comentario']) ?></textarea>

        <button class="bg-blue-600 text-white px-4 py-2 rounded">
            Guardar cambios
        </button>

    </form>
</div>

<?php include __DIR__ . "/../footer.php"; ?>