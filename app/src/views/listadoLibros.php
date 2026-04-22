<?php
require __DIR__ . "/../../vendor/autoload.php";
use App\Models\Basedatos;

$basedatos = new Basedatos();

$libros = $basedatos->obtener_listado_Libros();
require __DIR__ . "/layout.php";

$busqueda = $_GET['busqueda'] ?? '';

if (!empty($busqueda)) {
    $libros = $basedatos->buscarLibros($busqueda);
} else {
    $libros = $basedatos->obtener_listado_Libros();
}

function resaltar($texto, $busqueda) {
    if (empty($busqueda)) return $texto;

    return preg_replace(
        "/" . preg_quote($busqueda, '/') . "/i",
        '<span class="bg-blue-200 text-blue-900 px-1 rounded">$0</span>',
        $texto
    );
}

?>

<form method="GET" class="mb-6 flex gap-2">
    <input type="text" name="busqueda" placeholder="Buscar por título, autor, género o propietario..."
           class="border p-2 rounded w-full"
           value="<?= $_GET['busqueda'] ?? '' ?>">

    <button class="bg-blue-600 text-white px-4 rounded hover:bg-blue-700">
        Buscar
    </button>
</form>

<?php if (empty($libros)): ?>

    <p>No se encontraron resultados</p>

<?php else: ?>
    <?php if (!empty($busqueda)): ?>
        <p class="text-sm text-gray-500">
            Resultado para: "<?= htmlspecialchars($busqueda) ?>"
        </p>
    <?php endif; ?>

<h1 class="text-4xl font-bold mb-8 text-gray-800">Libros disponibles</h1>
<div class="w-full max-w-5xl bg-white shadow-lg rounded-xl overflow-hidden">
    <table border="1" cellpadding="8" class="min-w-full text-left">
        <thead class="bg-blue-600 text-white">
            <tr>
                <th class="px-6 py-3">Portada</th>
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
                
            <td> <?php if (!empty($libro['caratula'])): ?>
                    <img src="<?= BASE_URL ?>public/uploads/<?= $libro['caratula'] ?>">
                <?php else: ?>
                    <span>Sin imagen</span>
                <?php endif; ?>
            </td>

            <td class="px-6 py-4 font-medium text-gray-900"><?= resaltar(htmlspecialchars($libro['titulo']), $busqueda) ?></td>

            <td class="px-6 py-4 text-gray-700"><?= resaltar(htmlspecialchars($libro['autor']), $busqueda) ?></td>

            <td class="px-6 py-4">
            <span class="bg-gray-200 text-gray-800 px-3 py-1 rounded-full text-sm"><?= resaltar(htmlspecialchars($libro['genero']), $busqueda) ?></span></td>

            <td class="px-6 py-4 text-gray-700"><?= resaltar(htmlspecialchars((string)$libro['anio']), $busqueda) ?></td>

            <td class="px-6 py-4 text-gray-700"><?= resaltar(htmlspecialchars($libro['nombre']), $busqueda) ?></td>

            </tr><?php endforeach; ?>

        </tbody>
    </table>
</div>

<?php endif; ?>

<?php require __DIR__ . "/footer.php"; ?>
