<?php
session_start();
require __DIR__ . "/../../vendor/autoload.php";
require __DIR__ . "/layout.php";

if (!isset($_SESSION["usuario"])) {
    header("Location: " . BASE_URL . "/src/views/login.php");
    exit;
}
?>

<div class="max-w-xl mx-auto">

    <h1 class="text-4xl font-bold mb-2 text-gray-800 text-center">
        Añadir libro
    </h1>

    <p class="text-center text-gray-500 mb-8">
        Comparte un libro con la comunidad
    </p>

    <form action="crearLibro_process.php" method="POST" enctype="multipart/form-data"
          class="bg-white p-8 rounded-2xl shadow-lg space-y-5">

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Título</label>
            <input type="text" name="titulo" required
                   class="w-full border border-gray-300 p-3 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Autor</label>
            <input type="text" name="autor" required
                   class="w-full border border-gray-300 p-3 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Género</label>
            <input type="text" name="genero"
                   class="w-full border border-gray-300 p-3 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Año</label>
            <input type="number" name="anio"
                   class="w-full border border-gray-300 p-3 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Carátula</label>

            <input type="file" name="caratula" accept="image/*"
                   class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4
                          file:rounded-lg file:border-0 file:text-sm file:font-semibold
                          file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">

            <img id="preview" class="mt-4 hidden w-32 h-44 object-cover rounded-lg shadow">
        </div>

        <button
            class="w-full bg-blue-600 text-white py-3 rounded-xl font-semibold hover:bg-blue-700 transition shadow">
            Subir libro
        </button>

    </form>

</div>

<?php require __DIR__ . "/footer.php"; ?>

<script>
document.querySelector('input[name="caratula"]').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('preview');

    if (file) {
        preview.src = URL.createObjectURL(file);
        preview.classList.remove('hidden');
    }
});
</script>