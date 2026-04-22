<?php 
session_start();
require __DIR__ . "/../../vendor/autoload.php";
require __DIR__ . "/layout.php";
?>

<div class="max-w-md mx-auto mt-10">

    <h1 class="text-4xl font-bold text-center text-gray-800 mb-2">
        Iniciar sesión
    </h1>

    <p class="text-center text-gray-500 mb-8">
        Accede a tu cuenta para gestionar tus libros
    </p>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="mb-6 bg-red-100 text-red-700 px-4 py-2 rounded-lg text-sm text-center">
            <?= $_SESSION['error']; unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <form action="login_process.php" method="post"
          class="bg-white shadow-lg rounded-2xl p-8 space-y-5">

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Correo electrónico
            </label>
            <input type="email" name="email" required
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
                value="<?= isset($_SESSION["old_email"]) ? htmlspecialchars($_SESSION["old_email"]) : "" ?>">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Contraseña
            </label>
            <input type="password" name="password" required
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
        </div>

        <button type="submit"
            class="w-full bg-blue-600 text-white font-semibold py-3 rounded-xl hover:bg-blue-700 transition shadow">
            Entrar
        </button>

    </form>

    <div class="flex items-center my-6">
        <div class="flex-1 h-px bg-gray-300"></div>
        <span class="px-3 text-gray-400 text-sm">o</span>
        <div class="flex-1 h-px bg-gray-300"></div>
    </div>

    <div class="flex justify-center">
        <?php require __DIR__ . "/partials/boton_de_google.php"; ?>
    </div>

    <p class="text-center text-sm text-gray-500 mt-6">
        ¿No tienes cuenta?
        <a href="<?= BASE_URL ?>src/views/register.php" class="text-blue-600 font-medium hover:underline">
            Regístrate
        </a>
    </p>

</div>

<?php require __DIR__ . "/footer.php"; ?>