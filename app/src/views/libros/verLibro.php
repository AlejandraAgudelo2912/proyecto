<?php
session_start();
require __DIR__ . "/../../../vendor/autoload.php";
require __DIR__ . "/../layout.php";

use App\Models\LibroModel;
use App\Models\ComentarioModel;
use App\Models\ValoracionModel;

if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit;
}

$libroModel = new LibroModel();
$comentarioModel = new ComentarioModel();
$valoracionModel = new ValoracionModel();

$id = $_GET['id'] ?? null;

if (!$id) {
    die("Libro no encontrado");
}

$libro = $libroModel->obtenerLibroPorId($id);

if (!$libro) {
    die("Libro no existe");
}

$comentarios = $comentarioModel->obtenerComentarios($libro['id']);
$media = $valoracionModel->obtenerMediaValoraciones($libro['id']);
$valoraciones = $valoracionModel->obtenerValoraciones($libro['id']);

$miValoracion = $valoracionModel->obtenerValoracionUsuario(
    $libro['id'],
    $_SESSION['usuario']['id']
);

$esPropio = $libro['id_usuario'] == $_SESSION['usuario']['id'];
$estaPrestado = $libro['prestado'];
$loTengoYo = $libro['prestado_a'] == $_SESSION['usuario']['id'];
?>

<div class="max-w-5xl mx-auto">

    <div class="bg-white rounded-2xl shadow-lg p-8 flex flex-col md:flex-row gap-8">

        <!-- IMAGEN -->
        <div class="w-full md:w-1/3">
            <?php if ($libro['caratula']): ?>
                <img src="<?= BASE_URL ?>public/uploads/<?= $libro['caratula'] ?>"
                     class="w-full h-[400px] object-cover rounded-xl shadow">
            <?php else: ?>
                <div class="w-full h-[400px] bg-gray-100 flex items-center justify-center rounded-xl text-gray-400">
                    Sin imagen
                </div>
            <?php endif; ?>
        </div>

        <!-- INFO -->
        <div class="flex-1 flex flex-col justify-between">

            <div>

                <h1 class="text-3xl font-bold mb-2">
                    <?= htmlspecialchars($libro['titulo']) ?>
                </h1>

                <p class="text-gray-600 mb-2">
                    <?= htmlspecialchars($libro['autor']) ?>
                </p>

                <span class="inline-block bg-gray-200 px-3 py-1 rounded-full text-sm mb-4">
                    <?= htmlspecialchars($libro['genero']) ?>
                </span>

                <p class="text-gray-500 mb-1">
                    Año: <?= htmlspecialchars($libro['anio']) ?>
                </p>

                <p class="text-gray-500 mb-4">
                    Propietario: <?= htmlspecialchars($libro['nombre']) ?>
                </p>
                <p class="mt-2 text-yellow-500">
                    ⭐ <?= $media ?? 'Sin valoraciones' ?>
                </p>

            </div>

            <!-- BOTONES -->
            <div class="mt-6">

                <?php if (!$estaPrestado && !$esPropio): ?>

                    <a href="prestarLibro.php?id=<?= $libro['id'] ?>"
                       class="block text-center bg-green-500 text-white py-3 rounded-xl hover:bg-green-600 transition">
                        Coger libro
                    </a>

                <?php elseif ($estaPrestado && $loTengoYo): ?>

                    <a href="devolverLibro.php?id=<?= $libro['id'] ?>"
                       class="block text-center bg-blue-500 text-white py-3 rounded-xl hover:bg-blue-600 transition">
                        Devolver libro
                    </a>

                <?php elseif ($estaPrestado): ?>

                    <div class="bg-red-100 text-red-600 text-center py-3 rounded-xl">
                        Actualmente prestado
                    </div>

                <?php else: ?>

                    <div class="bg-gray-100 text-gray-600 text-center py-3 rounded-xl">
                        Este libro es tuyo
                    </div>

                <?php endif; ?>

            </div>

        </div>  

    </div>
        <div class="mt-8">
            <div class="flex gap-4 mb-6">

                <button id="btnValorar"
                    onclick="mostrarSeccion('valorar')"
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg transition">
                    Valorar
                </button>

                <button id="btnComentar"
                    onclick="mostrarSeccion('comentar')"
                    class="px-4 py-2 bg-gray-200 rounded-lg transition">
                    Comentar
                </button>

            </div>
            <div id="seccionValorar">

            <div class="mt-8 mb-8">

                <h2 class="text-xl font-semibold mb-3">⭐ Valoración</h2>

                <?php if ($miValoracion): ?>

                    <!-- YA HA VALORADO -->
                    <div class="bg-green-50 border border-green-200 p-4 rounded-xl">

                        <p class="text-green-700 font-medium mb-2">
                            Ya has valorado este libro
                        </p>

                        <p class="text-yellow-500 text-lg mb-2">
                            <?= str_repeat("★", $miValoracion['puntuacion']) ?>
                            <?= str_repeat("☆", 5 - $miValoracion['puntuacion']) ?>
                        </p>

                        <?php if (!empty($miValoracion['comentario'])): ?>
                            <p class="text-gray-700 text-sm">
                                “<?= htmlspecialchars($miValoracion['comentario']) ?>”
                            </p>
                        <?php endif; ?>

                        <p class="text-xs text-gray-400 mt-2 italic">
                            Puedes cambiar tu valoración cuando quieras
                        </p>

                    </div>

                <?php else: ?>

                    <!-- FORMULARIO -->
                    <form action="../comentarios/valorarLibro.php" id="formValoracion" method="POST" class="bg-white p-6 rounded-xl shadow-md">

                        <input type="hidden" name="id_libro" value="<?= $libro['id'] ?>">

                        <div id="estrellas" class="flex gap-1 text-2xl cursor-pointer mb-3">
                            <span data-value="1">☆</span>
                            <span data-value="2">☆</span>
                            <span data-value="3">☆</span>
                            <span data-value="4">☆</span>
                            <span data-value="5">☆</span>
                        </div>

                        <textarea name="comentario"
                            placeholder="Escribe una reseña (opcional)..."
                            class="w-full border p-3 rounded-lg mb-3"></textarea>

                        <input type="hidden" name="puntuacion" id="puntuacion">

                        <button class="bg-blue-600 text-white px-4 py-2 rounded">
                            Enviar valoración
                        </button>

                    </form>

                <?php endif; ?>

            </div>
            </div>
            <div id="seccionComentar" class="hidden">
            <h2 class="text-xl font-semibold mb-3">Comentarios</h2>

            <form action="../comentarios/crearComentario.php" method="POST" class="mb-6">

                <input type="hidden" name="id_libro" value="<?= $libro['id'] ?>">

                <textarea name="comentario"
                    placeholder="Escribe un comentario..."
                    class="w-full border p-3 rounded-lg mb-2"
                    required></textarea>

                <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Comentar
                </button>

            </form>
                      </div>
            <?php if (empty($valoraciones)): ?>

                <p class="text-gray-400 italic mb-4">
                    Aún no hay valoraciones
                </p>

            <?php else: ?>

                <div class="space-y-4 mb-6">

                    <?php foreach ($valoraciones as $v): ?>

                        <div class="bg-white p-4 rounded-xl shadow hover:shadow-md transition">

                            <!-- CABECERA -->
                            <div class="flex justify-between items-center mb-2">

                                <div class="flex items-center gap-2">

                                    <span class="font-semibold text-gray-800">
                                        <?= htmlspecialchars($v['nombre']) ?>
                                    </span>

                                    <!-- estrellas -->
                                    <span class="text-yellow-500 text-sm">
                                        <?= str_repeat("★", $v['puntuacion']) ?>
                                        <?= str_repeat("☆", 5 - $v['puntuacion']) ?>
                                    </span>

                                </div>

                                <!-- fecha -->
                                <span class="text-xs text-gray-400">
                                    <?= date("d/m/Y", strtotime($v['fecha'])) ?>
                                </span>

                            </div>

                            <!-- COMENTARIO -->
                            <?php if (!empty($v['comentario'])): ?>
                                <p class="text-gray-700 text-sm leading-relaxed">
                                    <?= htmlspecialchars($v['comentario']) ?>
                                </p>
                            <?php else: ?>
                                <p class="text-gray-400 text-sm italic">
                                    Sin comentario
                                </p>
                            <?php endif; ?>

                        </div>

                    <?php endforeach; ?>

                </div>

            <?php endif; ?>

            <?php if (empty($comentarios)): ?>

                <p class="text-gray-500">No hay comentarios aún</p>

            <?php else: ?>

                <?php foreach ($comentarios as $c): ?>

                    <div class="bg-white p-4 rounded-lg shadow mb-3">

                        <p class="text-sm text-gray-500 mb-1">
                            <?= htmlspecialchars($c['nombre']) ?> · <?= $c['fecha'] ?>
                        </p>

                        <p><?= htmlspecialchars($c['comentario']) ?></p>

                    </div>

                <?php endforeach; ?>

            <?php endif; ?>
          

        </div>
    </div>
</div>

<script>
const estrellas = document.querySelectorAll("#estrellas span");
const inputPuntuacion = document.getElementById("puntuacion");

function pintar(valor) {
    estrellas.forEach(e => {
        e.textContent = e.dataset.value <= valor ? "★" : "☆";
    });
}

estrellas.forEach(estrella => {
    estrella.addEventListener("click", () => {
        const valor = estrella.dataset.value;
        inputPuntuacion.value = valor;
        pintar(valor);
    });

    estrella.addEventListener("mouseover", () => {
        pintar(estrella.dataset.value);
    });
});

document.getElementById("formValoracion").addEventListener("submit", function(e) {
    e.preventDefault();

    if (!inputPuntuacion.value) {
        alert("Selecciona una puntuación");
        return;
    }

    const datos = new FormData(this);

    fetch("../comentarios/valorarLibro.php", {
        method: "POST",
        body: datos
    })
    .then(() => {
        alert("Reseña guardada");
        location.reload();
    });
});

function mostrarSeccion(seccion) {

    const valorar = document.getElementById("seccionValorar");
    const comentar = document.getElementById("seccionComentar");

    const btnValorar = document.getElementById("btnValorar");
    const btnComentar = document.getElementById("btnComentar");

    // ocultar todo
    valorar.style.display = "none";
    comentar.style.display = "none";

    // reset botones
    btnValorar.classList.remove("bg-blue-600", "text-white");
    btnComentar.classList.remove("bg-blue-600", "text-white");

    btnValorar.classList.add("bg-gray-200");
    btnComentar.classList.add("bg-gray-200");

    // activar
    if (seccion === "valorar") {
        valorar.style.display = "block";
        btnValorar.classList.add("bg-blue-600", "text-white");
        btnValorar.classList.remove("bg-gray-200");
    } else {
        comentar.style.display = "block";
        btnComentar.classList.add("bg-blue-600", "text-white");
        btnComentar.classList.remove("bg-gray-200");
    }
}
</script>

<?php require __DIR__ . "/../footer.php"; ?>