<?php
session_start();
require __DIR__ . "/../../vendor/autoload.php";
require __DIR__ . "/layout.php";

if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit();
}
?>

<div class="max-w-md mx-auto mt-10">

    <h1 class="text-3xl font-bold text-center mb-6">
        Importar libros desde CSV
    </h1>

    <form action="importarCSV_process.php" method="POST" enctype="multipart/form-data"
          class="bg-white p-6 rounded-xl shadow space-y-4">

        <div>
            <label class="block text-sm mb-1">Tipo de importación</label>
            <select name="tipo" id="tipo" class="w-full border p-2 rounded">
                <option value="csv">Solo CSV</option>
                <option value="zip">CSV + imágenes (ZIP)</option>
            </select>
        </div>

        <div id="csvInput">
            <label class="block text-sm mb-1">Archivo CSV</label>
            <input type="file" name="csv" accept=".csv"
                class="w-full border p-2 rounded">
        </div>

        <div id="zipInput" class="hidden">
            <label class="block text-sm mb-1">Archivo ZIP (opcional)</label>
            <input type="file" name="zip" accept=".zip"
                class="w-full border p-2 rounded">
        </div>

        <button class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
            Importar
        </button>

    </form>

</div>

<div class="mt-8 bg-blue-50 border border-blue-200 rounded-xl p-5 text-sm text-gray-700">

    <h2 class="font-semibold text-blue-700 mb-3">
        Cómo subir un archivo ZIP correctamente
    </h2>

    <ul class="list-disc pl-5 space-y-2">

        <li>
            El archivo debe ser un <strong>.zip</strong>
        </li>

        <li>
            Dentro del ZIP debe haber un archivo llamado:
            <code class="bg-white px-1 rounded">libros.csv</code>
        </li>

        <li>
            También debe existir una carpeta llamada:
            <code class="bg-white px-1 rounded">imagenes</code>
        </li>

        <li>
            Dentro de <code class="bg-white px-1 rounded">imagenes</code> deben estar las imágenes de los libros
        </li>

        <li>
            Los nombres de las imágenes deben coincidir EXACTAMENTE con los del CSV
        </li>

    </ul>

    <div class="mt-4 p-3 bg-white border rounded text-xs text-gray-600">
        <strong>Estructura esperada:</strong><br><br>

        miArchivo.zip<br>
        ├── libros.csv<br>
        └── imagenes/<br>
        &nbsp;&nbsp;&nbsp;&nbsp;├── dune.jpg<br>
        &nbsp;&nbsp;&nbsp;&nbsp;├── 1984.jpg<br>
        &nbsp;&nbsp;&nbsp;&nbsp;└── hobbit.jpg
    </div>
    
    <p class="text-xs text-gray-400 mt-2">
        ⚠️ Si la estructura no es correcta, el sistema no podrá importar los datos
    </p>

</div>

<script>
    const tipo = document.getElementById("tipo");
    const csvInput = document.getElementById("csvInput");
    const zipInput = document.getElementById("zipInput");

    tipo.addEventListener("change", () => {
        if (tipo.value === "csv") {
            csvInput.classList.remove("hidden");
            zipInput.classList.add("hidden");
        } else {
            csvInput.classList.add("hidden");
            zipInput.classList.remove("hidden");
        }
    });
</script>

<?php require __DIR__ . "/footer.php"; ?>