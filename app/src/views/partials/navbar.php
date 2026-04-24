<?php
if (session_status() === PHP_SESSION_NONE) session_start();
define('BASE_URL', 'http://' . $_SERVER['HTTP_HOST'] . '/prestamo_de_libros/app/');
?>

<nav class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-md">
    <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">

        <div class="font-bold text-2xl tracking-wide">
            Prestamo de libros
        </div>

        <div class="flex items-center gap-6">

            <a href="<?= BASE_URL ?>public/index.php" 
               class="hover:text-gray-200 transition font-medium">
               Inicio
            </a>

            <?php if (isset($_SESSION["usuario"])): ?>

                <a href="<?= BASE_URL ?>src/views/listadoLibros.php" 
                   class="hover:text-gray-200 transition font-medium">
                   Libros
                </a>

                <a href="<?= BASE_URL ?>src/views/crearLibro.php" 
                   class="bg-white text-blue-600 px-4 py-1 rounded-full font-semibold hover:bg-gray-100 transition">
                   + Añadir
                </a>

                <a href="<?= BASE_URL ?>src/views/importarCSV.php" 
                   class="hover:text-gray-200 transition font-medium">
                   Importar CSV
                </a>

                <div class="flex items-center gap-3">

                    <span class="text-sm opacity-90">
                        <?= htmlspecialchars($_SESSION["usuario"]["nombre"]) ?>
                    </span>

                    <a href="<?= BASE_URL ?>src/views/logout.php" 
                       class="bg-red-500 px-3 py-1 rounded-full text-sm hover:bg-red-600 transition">
                       Salir
                    </a>

                </div>

            <?php else: ?>

                <a href="<?= BASE_URL ?>src/views/login.php" 
                   class="hover:text-gray-200 transition font-medium">
                   Login
                </a>

                <a href="<?= BASE_URL ?>src/views/register.php" 
                   class="bg-white text-blue-600 px-4 py-1 rounded-full font-semibold hover:bg-gray-100 transition">
                   Registro
                </a>

            <?php endif; ?>

        </div>
    </div>
</nav>