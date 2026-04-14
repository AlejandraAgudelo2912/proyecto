<?php 
session_start();
require __DIR__ . "/../../vendor/autoload.php";
require __DIR__ . "/layout.php";
?>

<h1 class="text-4xl font-bold mb-8 text-gray-800">Registro</h1>

<?php if (isset($_SESSION['error'])): ?>
    <div class="mb-4 text-red-600 font-bold">
        <?= $_SESSION['error']; unset($_SESSION['error']); ?>
    </div>
<?php endif; ?>

<form action="register_process.php" method="post" class="w-full max-w-sm bg-white shadow-md rounded-lg p-6">

    <div class="mb-4">
        <label>Nombre:</label>
        <input type="text" name="nombre" required class="w-full border p-2 rounded"
        value="<?= $_SESSION['old_nombre'] ?? '' ?>">
    </div>

    <div class="mb-4">
        <label>Primer Apellido:</label>
        <input type="text" name="apellido1" required class="w-full border p-2 rounded"
        value="<?= $_SESSION['old_apellido1'] ?? '' ?>">
    </div>   

    <div class="mb-4">
        <label>Segundo Apellido (opcional):</label>
        <input type="text" name="apellido2" class="w-full border p-2 rounded"
        value="<?= $_SESSION['old_apellido2'] ?? '' ?>">
    </div>

    <div class="mb-4">
        <label>Email:</label>
        <input type="email" name="email" required class="w-full border p-2 rounded"
        value="<?= $_SESSION['old_email'] ?? '' ?>">
    </div>

    <div class="mb-4">
        <label>Contraseña:</label>
        <input type="password" name="password" required class="w-full border p-2 rounded">
    </div>

    <div class="mb-4">
        <label>Confirmar contraseña:</label>
        <input type="password" name="password_confirm" required class="w-full border p-2 rounded">
    </div>


    <button class="w-full bg-blue-600 text-white py-2 rounded">
        Registrarse
    </button>

</form>