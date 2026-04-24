<?php
session_start();
require __DIR__ . "/../vendor/autoload.php";
require __DIR__ . "/../src/views/layout.php";

use App\Models\Basedatos;

$basedatos = new Basedatos();

if ($basedatos->getConexion() == null) {
    die("Error de conexión");
}

$libros = $basedatos->obtener_listado_Libros();
?>

<!-- HERO -->
<div class="text-center py-12">

    <h1 class="text-5xl font-extrabold text-gray-800 mb-4">
        Descubre libros
    </h1>

    <p class="text-gray-500 text-lg mb-8">
        Explora, comparte y encuentra tu próxima lectura
    </p>

    <?php if (!isset($_SESSION["usuario"])): ?>
        <div class="flex justify-center gap-4">
            <a href="<?= BASE_URL ?>src/views/login.php"
               class="bg-blue-600 text-white px-6 py-3 rounded-xl shadow hover:bg-blue-700 transition">
                Iniciar sesión
            </a>

            <a href="<?= BASE_URL ?>src/views/register.php"
               class="bg-white border border-blue-600 text-blue-600 px-6 py-3 rounded-xl shadow hover:bg-blue-50 transition">
                Registrarse
            </a>
        </div>
    <?php endif; ?>

</div>

<!-- LIBROS (DISCOVER) -->
<div class="mt-10">

    <h2 class="text-2xl font-bold mb-6 text-gray-800">
        Libros recientes
    </h2>

    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-6 gap-6">

        <?php foreach ($libros as $libro): ?>

            <div class="bg-white rounded-2xl shadow hover:shadow-xl hover:scale-105 transition duration-300 overflow-hidden">

                <!-- Imagen -->
                <?php if (!empty($libro['caratula'])): ?>
                    <img src="<?= BASE_URL ?>public/uploads/<?= $libro['caratula'] ?>"
                         class="w-full h-48 object-cover">
                <?php else: ?>
                    <div class="w-full h-48 bg-gray-100 flex items-center justify-center text-gray-400 text-sm">
                        Sin imagen
                    </div>
                <?php endif; ?>

                <!-- Info -->
                <div class="p-3">

                    <h3 class="font-semibold text-sm truncate">
                        <?= htmlspecialchars($libro['titulo']) ?>
                    </h3>

                    <p class="text-xs text-gray-500 truncate">
                        <?= htmlspecialchars($libro['autor']) ?>
                    </p>

                </div>

            </div>

        <?php endforeach; ?>

    </div>

</div>

<?php require __DIR__ . "/../src/views/footer.php"; ?>