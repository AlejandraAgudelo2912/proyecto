<?php
    session_start();
    require __DIR__ . "/../vendor/autoload.php";
    require __DIR__ . "/../src/views/layout.php";
    use App\Models\Basedatos;

    $basedatos = new Basedatos();

    if ($basedatos->getConexion()==null){
        $mensaje = "ERROR en la conexión a la base de datos";
        logger()->error($mensaje);
        die;
    } 
?>

<h1 class="text-4xl font-bold mb-8 text-gray-800">Bienvenid@ a la App</h1>
    <?php if (isset($_SESSION["usuario"])): ?>
        <p class="text-lg text-gray-600 mb-6">Hola, <?= htmlspecialchars($_SESSION["usuario"]["nombre"]) ?>. Bienvenido a tu panel de control.</p>
        <a href="../src/views/listadoLibros.php" 
        class="inline-block bg-blue-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-700 transition">
            Ver libros
        </a>
    <?php else: ?>

    <p class="text-lg text-gray-600 mb-6">Inicia sesión para acceder a tu cuenta y gestionar tus préstamos de libros.</p>
    <a href="../src/views/login.php"
    class="inline-block bg-blue-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-700 transition">
    Iniciar sesión
    </a>
    <?php endif; ?>

