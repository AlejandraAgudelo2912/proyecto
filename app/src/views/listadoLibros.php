<?php
session_start();
require __DIR__ . "/../../vendor/autoload.php";
use App\Models\Basedatos;

$basedatos = new Basedatos();

if ($basedatos->getConexion() == null) {
    die("Error de conexión a la base de datos");
}

$busqueda = $_GET['busqueda'] ?? '';

if (!empty($busqueda)) {
    $libros = $basedatos->buscarLibros($busqueda);
} else {
    $libros = $basedatos->obtener_listado_Libros();
}

function resaltar($texto, $busqueda) {
    $texto = (string)$texto;

    if (empty($busqueda)) return htmlspecialchars($texto);

    return preg_replace(
        "/" . preg_quote($busqueda, '/') . "/i",
        '<mark class="bg-blue-200 text-blue-900 px-1 rounded">$0</mark>',
        htmlspecialchars($texto)
    );
}

require __DIR__ . "/layout.php";
?>

<form method="GET" class="mb-6 flex gap-2">
    <input type="text" name="busqueda"
           placeholder="Buscar por título, autor, género o propietario..."
           class="border p-2 rounded w-full"
           value="<?= htmlspecialchars($busqueda) ?>">

    <button class="bg-blue-600 text-white px-4 rounded hover:bg-blue-700 transition">
        Buscar
    </button>
</form>

<?php if (empty($libros)): ?>

    <p class="text-gray-500">No se encontraron resultados</p>

<?php else: ?>

    <?php if (!empty($busqueda)): ?>
        <p class="text-sm text-gray-500 mb-4">
            Resultado para: "<strong><?= htmlspecialchars($busqueda) ?></strong>"
        </p>
    <?php endif; ?>

    <h1 class="text-4xl font-bold mb-8 text-gray-800">Libros disponibles</h1>

    <div class="w-full max-w-6xl bg-white shadow-lg rounded-xl overflow-hidden">
        <table class="min-w-full text-left">

            <thead class="bg-blue-600 text-white">
                <tr>
                    <th class="px-6 py-3">Portada</th>
                    <th class="px-6 py-3">Título</th>
                    <th class="px-6 py-3">Autor</th>
                    <th class="px-6 py-3">Género</th>
                    <th class="px-6 py-3">Año</th>
                    <th class="px-6 py-3">Propietario</th>
                    <th class="px-6 py-3">Estado</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200">

                <?php foreach ($libros as $libro): ?>

                <?php
                $esPropio = $libro['id_usuario'] == $_SESSION['usuario']['id'];
                $estaPrestado = $libro['prestado'];
                $loTengoYo = $libro['prestado_a'] == $_SESSION['usuario']['id'];
                ?>

                <tr class="hover:bg-gray-50 transition">

                    <td class="px-6 py-4">
                        <?php if (!empty($libro['caratula'])): ?>
                            <img src="<?= BASE_URL ?>public/uploads/<?= $libro['caratula'] ?>"
                                 class="w-20 h-28 object-cover rounded shadow">
                        <?php else: ?>
                            <span class="text-gray-400 text-sm">Sin imagen</span>
                        <?php endif; ?>
                    </td>

                    <td class="px-6 py-4 font-medium text-gray-900">
                        <?= resaltar($libro['titulo'], $busqueda) ?>
                    </td>

                    <td class="px-6 py-4 text-gray-700">
                        <?= resaltar($libro['autor'], $busqueda) ?>
                    </td>

                    <td class="px-6 py-4">
                        <span class="bg-gray-200 text-gray-800 px-3 py-1 rounded-full text-sm">
                            <?= resaltar($libro['genero'], $busqueda) ?>
                        </span>
                    </td>

                    <td class="px-6 py-4 text-gray-700">
                        <?= resaltar($libro['anio'], $busqueda) ?>
                    </td>

                    <td class="px-6 py-4 text-gray-700">
                        <?= resaltar($libro['nombre'], $busqueda) ?>
                    </td>

                    <td class="px-6 py-4">

                        <?php if (!$estaPrestado && !$esPropio): ?>

                            <a href="prestarLibro.php?id=<?= $libro['id'] ?>"
                               class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm hover:bg-green-200 transition">
                                Coger
                            </a>

                        <?php elseif ($estaPrestado && $loTengoYo): ?>

                            <a href="devolverLibro.php?id=<?= $libro['id'] ?>"
                               class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm hover:bg-blue-200 transition">
                                Devolver
                            </a>

                        <?php elseif ($estaPrestado): ?>

                            <span class="bg-red-100 text-red-600 px-3 py-1 rounded-full text-sm">
                                Prestado
                            </span>

                        <?php else: ?>

                            <span class="bg-gray-100 text-gray-600 px-3 py-1 rounded-full text-sm">
                                Tu libro
                            </span>

                        <?php endif; ?>

                    </td>

                </tr>

                <?php endforeach; ?>

            </tbody>
        </table>
    </div>

<?php endif; ?>

<?php require __DIR__ . "/footer.php"; ?>