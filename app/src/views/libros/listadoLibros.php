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

$idUsuarioFiltro = ($disponibilidad === 'mios') ? $_SESSION['usuario']['id'] : null;
$dispFiltro = ($disponibilidad === 'mios') ? '' : $disponibilidad;

if (!empty($busqueda)) {
    $libros = $libroModel->buscarLibros($busqueda, $orden, $dispFiltro, $idUsuarioFiltro);
} else {
    $libros = $libroModel->obtener_listado_Libros($orden, $dispFiltro, $idUsuarioFiltro);
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
    <div class="relative">
    <div class="flex items-center bg-white rounded-full shadow px-4 py-2 w-full">
        <input type="text" name="busqueda"
                id="busquedaInput"
                placeholder="Buscar libros..."
                class="flex-1 outline-none bg-transparent px-2"
                value="<?= htmlspecialchars($busqueda) ?>"
                autocomplete="off">

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

            <option value="mios" <?= $disp == 'mios' ? 'selected' : '' ?>>
                Mis libros
            </option>

        </select>
    </div>

    <!-- Dropdown autocompletado -->
    <div id="autocompleteDropdown" 
         class="hidden absolute left-0 right-0 top-full mt-1 bg-white rounded-xl shadow-lg border border-gray-200 z-50 overflow-hidden max-h-72 overflow-y-auto">
    </div>
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

                <?php $gc = colorGenero($libro['genero'] ?? ''); ?>
                <span class="inline-block <?= $gc['bg'] ?> <?= $gc['text'] ?> border <?= $gc['border'] ?> px-3 py-1 rounded-full text-xs mb-2">
                    <?= resaltar($libro['genero'], $busqueda) ?>
                </span>

                <p class="text-xs text-gray-400">
                    <?= resaltar($libro['anio'], $busqueda) ?> · <?= resaltar($libro['nombre'], $busqueda) ?>
                </p>

                <?php if (!empty($libro['created_at'])): ?>
                    <p class="text-xs text-gray-300 mt-1 italic">
                        <?= tiempoRelativo($libro['created_at']) ?>
                    </p>
                <?php endif; ?>

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
const dropdown = document.getElementById("autocompleteDropdown");

let timeoutBusqueda = null;
let timeoutAutocompletado = null;
let indiceActivo = -1;

// Iconos por tipo de sugerencia
const iconosTipo = {
    titulo: '📖',
    autor: '✍️',
    genero: '🏷️'
};

const labelTipo = {
    titulo: 'Título',
    autor: 'Autor',
    genero: 'Género'
};

// Autocompletado
input.addEventListener("input", () => {
    const valor = input.value.trim();

    // Limpiar timeouts
    clearTimeout(timeoutAutocompletado);
    clearTimeout(timeoutBusqueda);

    if (valor.length < 2) {
        dropdown.classList.add("hidden");
        dropdown.innerHTML = "";
        return;
    }

    // Autocompletado rápido (200ms)
    timeoutAutocompletado = setTimeout(() => {
        fetch(`autocompletado.php?q=${encodeURIComponent(valor)}`)
            .then(res => res.json())
            .then(sugerencias => {
                if (sugerencias.length === 0) {
                    dropdown.classList.add("hidden");
                    dropdown.innerHTML = "";
                    return;
                }

                indiceActivo = -1;
                dropdown.innerHTML = sugerencias.map((s, i) => {
                    const textoResaltado = s.texto.replace(
                        new RegExp(`(${valor.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')})`, 'gi'),
                        '<strong class="text-blue-600">$1</strong>'
                    );
                    return `
                        <div class="autocomplete-item flex items-center gap-3 px-4 py-3 cursor-pointer hover:bg-blue-50 transition-colors border-b border-gray-100 last:border-0"
                             data-index="${i}" data-texto="${s.texto.replace(/"/g, '&quot;')}">
                            <span class="text-lg flex-shrink-0">${iconosTipo[s.tipo] || '🔍'}</span>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm truncate">${textoResaltado}</p>
                                <p class="text-xs text-gray-400">${labelTipo[s.tipo] || s.tipo}</p>
                            </div>
                        </div>
                    `;
                }).join('');

                dropdown.classList.remove("hidden");

                // Click en sugerencia
                dropdown.querySelectorAll(".autocomplete-item").forEach(item => {
                    item.addEventListener("click", () => {
                        input.value = item.dataset.texto;
                        dropdown.classList.add("hidden");
                        document.getElementById("formBusqueda").submit();
                    });
                });
            })
            .catch(() => {
                dropdown.classList.add("hidden");
            });
    }, 200);

    // Búsqueda live de resultados (500ms)
    timeoutBusqueda = setTimeout(() => {
        if (contenedor) {
            fetch(`?busqueda=${encodeURIComponent(valor)}`)
                .then(res => res.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, "text/html");
                    const nuevosResultados = doc.getElementById("resultadosLibros");
                    if (nuevosResultados) {
                        contenedor.innerHTML = nuevosResultados.innerHTML;
                    }
                });
        }
    }, 500);
});

// Navegación con teclado
input.addEventListener("keydown", (e) => {
    const items = dropdown.querySelectorAll(".autocomplete-item");
    if (items.length === 0) return;

    if (e.key === "ArrowDown") {
        e.preventDefault();
        indiceActivo = Math.min(indiceActivo + 1, items.length - 1);
        actualizarActivo(items);
    } else if (e.key === "ArrowUp") {
        e.preventDefault();
        indiceActivo = Math.max(indiceActivo - 1, 0);
        actualizarActivo(items);
    } else if (e.key === "Enter" && indiceActivo >= 0) {
        e.preventDefault();
        input.value = items[indiceActivo].dataset.texto;
        dropdown.classList.add("hidden");
        document.getElementById("formBusqueda").submit();
    } else if (e.key === "Escape") {
        dropdown.classList.add("hidden");
    }
});

function actualizarActivo(items) {
    items.forEach((item, i) => {
        item.classList.toggle("bg-blue-50", i === indiceActivo);
    });
    if (items[indiceActivo]) {
        items[indiceActivo].scrollIntoView({ block: "nearest" });
    }
}

// Cerrar dropdown al hacer click fuera
document.addEventListener("click", (e) => {
    if (!e.target.closest("#formBusqueda")) {
        dropdown.classList.add("hidden");
    }
});
</script>

<?php require __DIR__ . "/../footer.php"; ?>

