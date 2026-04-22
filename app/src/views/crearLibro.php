<?php
session_start();
require __DIR__ . "/../../vendor/autoload.php";
require __DIR__ . "/layout.php";

if (!isset($_SESSION["usuario"])) {
    header("Location: " . BASE_URL . "/src/views/login.php");
    exit;
}
?>

<h1 class="text-3xl font-bold mb-6">Añadir libro 📚</h1>

<form action="crearLibro_process.php" method="POST" enctype="multipart/form-data" class="max-w-md bg-white p-6 rounded shadow">

    <div class="mb-4">
        <label>Título</label>
        <input type="text" name="titulo" required class="w-full border p-2 rounded">
    </div>

    <div class="mb-4">
        <label>Autor</label>
        <input type="text" name="autor" required class="w-full border p-2 rounded">
    </div>

    <div class="mb-4">
        <label>Género</label>
        <input type="text" name="genero" class="w-full border p-2 rounded">
    </div>

    <div class="mb-4">
        <label>Año</label>
        <input type="number" name="anio" class="w-full border p-2 rounded">
    </div>

    <div class="mb-4">
        <label>Caratula</label>
        <input type="file" name="caratula" class="w-full border p-2 rounded">
    </div>

    <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        Subir libro
    </button>

</form>