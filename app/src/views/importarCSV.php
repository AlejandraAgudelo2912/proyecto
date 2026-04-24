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

        <input type="file" name="csv" accept=".csv" required
               class="w-full border p-2 rounded">

        <button class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
            Subir CSV
        </button>

    </form>

</div>

<?php require __DIR__ . "/footer.php"; ?>