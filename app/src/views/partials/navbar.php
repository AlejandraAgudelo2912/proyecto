<?php
if (session_status() === PHP_SESSION_NONE) session_start();
define ('BASE_URL','http://' . $_SERVER['HTTP_HOST'] . '/prestamo_de_libros/app/'); 
?>

<nav class="w-full bg-blue-600 text-white shadow-md">
    <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
    
        <div class="font-bold text-xl">
            Mi App
        </div>

        <div class="space-x-6">
            <a href="<?= BASE_URL ?>public/index.php" class="hover:text-gray-200 transition">Inicio</a>

            <?php if (isset($_SESSION["usuario"])): ?>
                <a href="<?= BASE_URL ?>src/views/listadoLibros.php" class="hover:text-gray-200 transition">Libros</a>
                <a href="<?= BASE_URL ?>src/views/logout.php" class="hover:text-gray-200 transition">Cerrar sesión</a>
                <a href="<?= BASE_URL ?>/src/views/crearLibro.php">Añadir libro</a>
            <?php else: ?>
                <a href="<?= BASE_URL ?>src/views/login.php" class="hover:text-gray-200 transition">Login</a>
                <a href="<?= BASE_URL ?>src/views/register.php" class="hover:text-gray-200 transition">Registro</a>
            <?php endif; ?>
        </div>
    </div>

</nav>