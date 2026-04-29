<?php
session_start();
require __DIR__ . "/../../../vendor/autoload.php";

use App\Models\LibroModel;

if (!isset($_SESSION["usuario"])) {
    header("Location: ../auth/login.php");
    exit;
}

$libroModel = new LibroModel();

$id = $_GET['id'];
$libro = $libroModel->obtenerLibroPorId($id);

// seguridad básica
if ($libro['id_usuario'] != $_SESSION['usuario']['id']) {
    die("No tienes permisos 💀");
}
?>

<?php include __DIR__ . "/../layout.php"; ?>

<div class="max-w-xl mx-auto bg-white p-6 rounded-xl shadow">

    <h1 class="text-2xl font-bold mb-4">Editar libro</h1>

    <form action="editarLibro_process.php" method="POST" enctype="multipart/form-data">

        <input type="hidden" name="id" value="<?= $libro['id'] ?>">

        <input type="text" name="titulo"
            value="<?= htmlspecialchars($libro['titulo']) ?>"
            class="w-full border p-2 mb-3 rounded">

        <input type="text" name="autor"
            value="<?= htmlspecialchars($libro['autor']) ?>"
            class="w-full border p-2 mb-3 rounded">

        <input type="text" name="genero"
            value="<?= htmlspecialchars($libro['genero']) ?>"
            class="w-full border p-2 mb-3 rounded">

        <input type="number" name="anio"
            value="<?= htmlspecialchars($libro['anio']) ?>"
            class="w-full border p-2 mb-3 rounded">

        <label class="block mb-2 font-medium">Nueva carátula</label>

        <input type="file" name="caratula" class="mb-3">

        <?php if ($libro['caratula']): ?>
            <p class="text-sm text-gray-500 mb-2">Actual:</p>
            <img src="<?= BASE_URL ?>public/uploads/<?= $libro['caratula'] ?>"
                class="w-32 rounded mb-4">
        <?php endif; ?>

        <button class="bg-blue-600 text-white px-4 py-2 rounded">
            Guardar cambios
        </button>

    </form>

</div>