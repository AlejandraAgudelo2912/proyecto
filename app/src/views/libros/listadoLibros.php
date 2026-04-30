<?php
session_start();
require __DIR__ . "/../../../vendor/autoload.php";

use App\Models\LibroModel;
use App\Models\PrestamosModel;

$prestamoModel = new PrestamosModel();

$libroModel = new LibroModel();


if (!isset($_SESSION["usuario"])) {
    header("Location: ../auth/login.php");
    exit();
}

$busqueda = $_GET['busqueda'] ?? '';
$orden = $_GET['orden'] ?? '';
$disponibilidad = $_GET['disponibilidad'] ?? '';

if (!empty($busqueda)) {
    $libros = $libroModel->buscarLibros($busqueda, $orden, $disponibilidad);
} else {
    $libros = $libroModel->obtener_listado_Libros($orden, $disponibilidad);
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

require __DIR__ . "/../layout.php";?>

<form id="formBusqueda" class="mb-8">
    <div class="flex items-center bg-white rounded-full shadow px-4 py-2 w-full">
        <input type="text" name="busqueda"
                id="busquedaInput"
                placeholder="Buscar libros..."
                class="flex-1 outline-none bg-transparent px-2"
                value="<?= htmlspecialchars($busqueda) ?>">

        <button class="bg-blue-600 text-white px-4 py-1 rounded-full hover:bg-blue-700 transition">
            Buscar
        </button>

        <a href="?" class="ml-4 text-gray-500 hover:text-gray-700 transition">
            Limpiar
        </a>

        <?php $orden = $_GET['orden'] ?? ''; ?>

        <select name="orden" class="ml-3 border rounded px-2 py-1 text-sm">
            <option value="">Ordenar</option>
            <option value="titulo" <?= $orden == 'titulo' ? 'selected' : '' ?>>Título</option>
            <option value="anio" <?= $orden == 'anio' ? 'selected' : '' ?>>Año</option>
        </select>

        <select name="disponibilidad" onchange="this.form.submit()" 
                class="ml-3 border rounded px-2 py-1 text-sm">

            <option value="">Todos</option>
            <?php $disp = $_GET['disponibilidad'] ?? ''; ?>

            <option value="disponible" <?= $disp == 'disponible' ? 'selected' : '' ?>>
                Disponibles
            </option>

            <option value="prestado" <?= $disp == 'prestado' ? 'selected' : '' ?>>
                No disponibles
            </option>

        </select>
    </div>
</form>
<?php if (!empty($busqueda)): ?>
    <p class="text-gray-500 mb-4">
        <?= count($libros) ?>
         resultados para: <strong><?= htmlspecialchars($busqueda) ?></strong>
    </p>
<?php endif; ?>

<?php if (empty($libros)): ?>

    <p class="text-gray-500">No se encontraron resultados</p>

<?php else: ?>

    <h1 class="text-3xl font-bold mb-8 text-gray-800">Libros disponibles</h1>

    <div id="resultadosLibros" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-5 gap-6">

        <?php foreach ($libros as $libro): ?>

            <?php
                $esPropio = $libro['id_usuario'] == $_SESSION['usuario']['id'];
                $estaPrestado = $libro['prestado'];
                $loTengoYo = $libro['prestado_a'] == $_SESSION['usuario']['id'];
                $miSolicitud = $prestamoModel->obtenerSolicitudUsuario(
                    $libro['id'],
                    $_SESSION['usuario']['id']
                );
            ?>

            <div class="bg-white rounded-2xl shadow-md p-4 hover:shadow-xl hover:scale-[1.02] transition-all duration-300">
                <a href="verLibro.php?id=<?= $libro['id'] ?>">
                <!-- Imagen -->
                <?php if (!empty($libro['caratula'])): ?>
                    <img src="<?= BASE_URL ?>public/uploads/<?= $libro['caratula'] ?>"
                         class="w-full h-56 object-cover rounded-lg mb-4">
                <?php else: ?>
                    <div class="w-full h-56 flex items-center justify-center bg-gray-100 rounded-lg mb-4 text-gray-400 text-sm">
                        Sin imagen
                    </div>
                <?php endif; ?>

                <!-- Info -->
                <h2 class="font-bold text-lg mb-1">
                    <?= resaltar($libro['titulo'], $busqueda) ?>
                </h2>

                <p class="text-gray-500 text-sm mb-2">
                    <?= resaltar($libro['autor'], $busqueda) ?>
                </p>

                <span class="inline-block bg-gray-200 text-gray-700 px-3 py-1 rounded-full text-xs mb-2">
                    <?= resaltar($libro['genero'], $busqueda) ?>
                </span>

                <p class="text-xs text-gray-400">
                    <?= resaltar($libro['anio'], $busqueda) ?> · <?= resaltar($libro['nombre'], $busqueda) ?>
                </p>

                <!-- Botones -->
                <div class="mt-4">

                   <?php if (!$estaPrestado && !$esPropio): ?>

                        <?php if ($miSolicitud && $miSolicitud['estado'] == 'pendiente'): ?>

                            <span class="block text-center bg-yellow-100 text-yellow-700 px-3 py-2 rounded-xl text-sm">
                                ⏳ Solicitud enviada
                            </span>

                        <?php else: ?>

                            <a href="prestarLibro.php?id=<?= $libro['id'] ?>"
                            class="block text-center bg-green-100 text-green-700 px-3 py-2 rounded-xl text-sm hover:bg-green-200 transition">
                                Solicitar
                            </a>

                        <?php endif; ?>

                    <?php elseif ($estaPrestado && $loTengoYo): ?>

                        <a href="devolverLibro.php?id=<?= $libro['id'] ?>"
                           class="block text-center bg-blue-100 text-blue-700 px-3 py-2 rounded-xl text-sm hover:bg-blue-200 transition">
                            Devolver
                        </a>

                    <?php elseif ($estaPrestado): ?>

                        <span class="block text-center bg-red-100 text-red-600 px-3 py-2 rounded-xl text-sm">
                            Prestado
                        </span>

                    <?php else: ?>

                        <span class="block text-center bg-gray-100 text-gray-600 px-3 py-2 rounded-xl text-sm">
                            Tu libro
                        </span>

                    <?php endif; ?>

                </div>
                </a>

            </div>

        <?php endforeach; ?>

    </div>

<?php endif; ?>
<script>
const input = document.getElementById("busquedaInput");
const contenedor = document.getElementById("resultadosLibros");

let timeout = null;

input.addEventListener("input", () => {
    clearTimeout(timeout);

    timeout = setTimeout(() => {

        fetch(`?busqueda=${encodeURIComponent(input.value)}`)
            .then(res => res.text())
            .then(html => {

                const parser = new DOMParser();
                const doc = parser.parseFromString(html, "text/html");

                const nuevosResultados = doc.getElementById("resultadosLibros");

                contenedor.innerHTML = nuevosResultados.innerHTML;

            });

    }, 400);
});
</script>

<?php require __DIR__ . "/../footer.php"; ?>

