<?php
session_start();
require __DIR__ . "/../vendor/autoload.php";
require __DIR__ . "/../src/views/layout.php";

use App\Models\Basedatos;

$basedatos = new Basedatos();

if ($basedatos->getConexion() == null) {
    $mensaje = "ERROR en la conexión a la base de datos";
    logger()->error($mensaje);
    die;
}
?>

<div class="text-center py-10">

    <h1 class="text-5xl font-extrabold text-gray-800 mb-4">
        Bienvenid@
    </h1>

    <p class="text-gray-500 text-lg mb-8">
        Comparte libros, descubre lecturas y conecta con otros usuarios
    </p>

    <?php if (isset($_SESSION["usuario"])): ?>

        <div class="space-y-4">
            <p class="text-xl text-gray-700">
                Hola, <span class="font-semibold"><?= htmlspecialchars($_SESSION["usuario"]["nombre"]) ?></span>
            </p>

            <a href="<?= BASE_URL ?>src/views/listadoLibros.php"
               class="inline-block bg-blue-600 text-white font-semibold px-6 py-3 rounded-xl shadow hover:bg-blue-700 transition">
                Ver libros
            </a>
        </div>

    <?php else: ?>

        <div class="space-y-4">
            <p class="text-gray-600">
                Inicia sesión para gestionar tus préstamos y explorar la comunidad
            </p>

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
        </div>

    <?php endif; ?>

</div>

<?php require __DIR__ . "/../src/views/footer.php"; ?>