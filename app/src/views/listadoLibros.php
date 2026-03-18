<?php
require __DIR__ . "/../../vendor/autoload.php";
use App\Models\Basedatos;

$basedatos = new Basedatos();

$libros = $basedatos->obtener_listado_Libros();
require __DIR__ . "/layout.php";
?>

<h1 class="text-4xl font-bold mb-8 text-gray-800">Libros disponibles</h1>
<div class="w-full max-w-5xl bg-white shadow-lg rounded-xl overflow-hidden">
    <table border="1" cellpadding="8" class="min-w-full text-left">
        <thead class="bg-blue-600 text-white">
            <tr>
                <th class="px-6 py-3">Título</th>
                <th class="px-6 py-3">Autor</th>
                <th class="px-6 py-3">Género</th>
                <th class="px-6 py-3">Año</th>
                <th class="px-6 py-3">Propietario</th>
            </tr>
        </thead>

        <tbody class="divide-y divide-gray-200">

            <?php foreach ($libros as $libro): ?>

            <tr class="hover:bg-gray-50 transition">

            <td class="px-6 py-4 font-medium text-gray-900"><?= htmlspecialchars($libro['titulo']) ?></td>

            <td class="px-6 py-4 text-gray-700"><?= htmlspecialchars($libro['autor']) ?></td>

            <td class="px-6 py-4">
            <span class="bg-gray-200 text-gray-800 px-3 py-1 rounded-full text-sm"><?= htmlspecialchars($libro['genero']) ?></span></td>

            <td class="px-6 py-4 text-gray-700"><?= htmlspecialchars($libro['anio']) ?></td>

            <td class="px-6 py-4 text-gray-700"><?= htmlspecialchars($libro['nombre']) ?></td>

            </tr><?php endforeach; ?>

        </tbody>
    </table>
</div>

<?php require __DIR__ . "/footer.php"; ?>
