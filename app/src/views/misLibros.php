<?php
session_start();
require __DIR__ . "/../../vendor/autoload.php";
require __DIR__ . "/layout.php";

use App\Models\Basedatos;

if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit;
}

$db = new Basedatos();
$idUsuario = $_SESSION['usuario']['id'];

$misLibros = $db->obtenerLibrosPropios($idUsuario);
$prestados = $db->obtenerLibrosPrestados($idUsuario);
?>

<div class="space-y-10">

    <h1 class="text-4xl font-bold text-gray-800">
        📚 Mis libros
    </h1>

    <!-- LIBROS PROPIOS -->
    <div>
        <h2 class="text-2xl font-semibold mb-4">📖 Libros que he subido</h2>

        <?php if (empty($misLibros)): ?>
            <p class="text-gray-500">No has añadido libros</p>
        <?php else: ?>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <?php foreach ($misLibros as $libro): ?>

                    <div class="bg-white p-4 rounded-xl shadow hover:shadow-lg transition">

                        <?php if ($libro['caratula']): ?>
                            <img src="<?= BASE_URL ?>public/uploads/<?= $libro['caratula'] ?>"
                                 class="w-full h-40 object-cover rounded mb-2">
                        <?php endif; ?>

                        <h3 class="font-bold"><?= htmlspecialchars($libro['titulo']) ?></h3>
                        <p class="text-sm text-gray-500"><?= htmlspecialchars($libro['autor']) ?></p>

                        <?php if ($libro['prestado']): ?>
                            <span class="text-red-500 text-sm">Prestado</span>
                        <?php else: ?>
                            <span class="text-green-500 text-sm">Disponible</span>
                        <?php endif; ?>

                    </div>

                <?php endforeach; ?>
            </div>

        <?php endif; ?>
    </div>

    <!-- LIBROS PRESTADOS -->
    <div>
        <h2 class="text-2xl font-semibold mb-4">📥 Libros que tengo prestados</h2>

        <?php if (empty($prestados)): ?>
            <p class="text-gray-500">No tienes libros prestados</p>
        <?php else: ?>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <?php foreach ($prestados as $libro): ?>

                    <div class="bg-white p-4 rounded-xl shadow hover:shadow-lg transition">

                        <?php if ($libro['caratula']): ?>
                            <img src="<?= BASE_URL ?>public/uploads/<?= $libro['caratula'] ?>"
                                 class="w-full h-40 object-cover rounded mb-2">
                        <?php endif; ?>

                        <h3 class="font-bold"><?= htmlspecialchars($libro['titulo']) ?></h3>
                        <p class="text-sm text-gray-500"><?= htmlspecialchars($libro['autor']) ?></p>

                        <span class="text-sm text-gray-400">
                            Propietario: <?= htmlspecialchars($libro['nombre']) ?>
                        </span>

                    </div>

                <?php endforeach; ?>
            </div>

        <?php endif; ?>
    </div>

</div>

<?php require __DIR__ . "/footer.php"; ?>