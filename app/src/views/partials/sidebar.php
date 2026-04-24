<?php
if (session_status() === PHP_SESSION_NONE) session_start();
define('BASE_URL', 'http://' . $_SERVER['HTTP_HOST'] . '/prestamo_de_libros/app/');
?>

<aside class="w-64 min-h-screen bg-white shadow-xl p-6 sticky top-0">

    <!-- TOP -->
    <div>
        <h2 class="text-xl font-bold mb-8 flex items-center gap-2">
            <span>Books</span>
        </h2>

        <nav class="flex flex-col gap-4 text-gray-600">

            <a href="<?= BASE_URL ?>public/index.php"
               class="hover:text-blue-600 transition font-medium">
               Discover
            </a>

            <a href="<?= BASE_URL ?>src/views/listadoLibros.php"
               class="hover:text-blue-600 transition font-medium">
               Libros
            </a>

            <a href="<?= BASE_URL ?>src/views/misLibros.php"
               class="hover:text-blue-600 transition font-medium">
               Mis préstamos
            </a>

            <a href="<?= BASE_URL ?>src/views/crearLibro.php"
               class="hover:text-blue-600 transition font-medium">
               Añadir
            </a>

            <a href="<?= BASE_URL ?>src/views/importarCSV.php"
               class="hover:text-blue-600 transition font-medium">
               Importar CSV
            </a>

        </nav>
    </div>

    <!-- BOTTOM (usuario) -->
    <?php if (isset($_SESSION["usuario"])): ?>
        <div class="mt-10 border-t pt-4">
            <p class="text-sm text-gray-500 mb-2">
                <?= htmlspecialchars($_SESSION["usuario"]["nombre"]) ?>
            </p>

            <a href="<?= BASE_URL ?>src/views/logout.php"
               class="text-red-500 text-sm hover:underline">
               Cerrar sesión
            </a>
        </div>
    <?php endif; ?>

</aside>