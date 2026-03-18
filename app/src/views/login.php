<?php 
session_start();
require __DIR__ . "/../../vendor/autoload.php";


require __DIR__ . "/layout.php";
?>

<h1 class="text-4xl font-bold mb-8 text-gray-800">Iniciar sesión</h1>
<form action="login_process.php" method="post" class="w-full max-w-sm bg-white shadow-md rounded-lg p-6">
    <div class="mb-4">
        <label for="email" class="block text-gray-700 font-bold mb-2">Correo electrónico:</label>
        <input type="email" id="email" name="email" required 
        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
        value="<?= isset($_SESSION["old_email"]) ? htmlspecialchars($_SESSION["old_email"]) : "" ?>">
    </div>
    <div class="mb-4">
        <label for="password" class="block text-gray-700 font-bold mb-2">Contraseña:</label>
        <input type="password" id="password" name="password" required 
        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
        value="<?= isset($_SESSION["old_password"]) ? htmlspecialchars($_SESSION["old_password"]) : "" ?>">
    </div>

    <button type="submit" class="w-full bg-blue-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-700 transition">Iniciar sesión</button>
</form>

<?php require __DIR__ . "/footer.php"; ?>