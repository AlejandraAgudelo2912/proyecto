<?php
session_start();
require __DIR__ . "/../../../vendor/autoload.php";
require __DIR__ . "/../layout.php";

use App\Models\Basedatos;

if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit;
}

$db = new Basedatos();

$id = $_GET['id'] ?? null;

if (!$id) {
    die("Libro no encontrado");
}

$libro = $db->obtenerLibroPorId($id);

if (!$libro) {
    die("Libro no existe");
}

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

</div>

<?php require __DIR__ . "/../footer.php"; ?>