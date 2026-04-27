<?php
session_start();
require __DIR__ . "/../../../vendor/autoload.php";
require __DIR__ . "/../layout.php";

use App\Models\UsuarioModel;
use App\Models\PrestamosModel;

if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit;
}

$usuarioModel = new UsuarioModel();
$prestamosModel = new PrestamosModel();

$idUsuario = $_SESSION['usuario']['id'];

$misLibros = $usuarioModel->obtenerLibrosPropios($idUsuario);
$prestados = $prestamosModel->obtenerLibrosPrestados($idUsuario);


?>

<div class="space-y-10">

    <h1 class="text-4xl font-bold text-gray-800">
        Mis libros
    </h1>

    <!-- LIBROS PROPIOS -->
    <div>
        <h2 class="text-2xl font-semibold mb-4">Libros que he subido</h2>

        <?php if (empty($misLibros)): ?>
            <p class="text-gray-500">No has añadido libros</p>
        <?php else: ?>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <?php foreach ($misLibros as $libro): ?>

                    <div class="bg-white p-4 rounded-xl shadow hover:shadow-lg transition">
                        <a href="verLibro.php?id=<?= $libro['id'] ?>">
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
                        </a>
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
                    <?php
                        $dias = null;

                        if ($libro['fecha_prestamo']) {
                            $fechaInicio = new DateTime($libro['fecha_prestamo']);
                            $hoy = new DateTime();
                            $dias = $hoy->diff($fechaInicio)->days;
                        }
                    ?>
                    <div class="bg-white p-4 rounded-xl shadow hover:shadow-lg transition">
                        <a href="verLibro.php?id=<?= $libro['id'] ?>">

                        <?php if ($libro['caratula']): ?>
                            <img src="<?= BASE_URL ?>public/uploads/<?= $libro['caratula'] ?>"
                                 class="w-full h-40 object-cover rounded mb-2">
                        <?php endif; ?>

                        <h3 class="font-bold"><?= htmlspecialchars($libro['titulo']) ?></h3>
                        <p class="text-sm text-gray-500"><?= htmlspecialchars($libro['autor']) ?></p>

                        <span class="text-sm text-gray-400">
                            Propietario: <?= htmlspecialchars($libro['nombre']) ?>
                        </span>

                        <?php
                            $color = "text-green-500";

                            if ($dias > 7) $color = "text-yellow-500";
                            if ($dias > 14) $color = "text-red-500";
                        ?>

                        <p class="text-xs <?= $color ?>">
                            <?= $dias ?> días prestado
                        </p>
                        </a>

                    </div>

                <?php endforeach; ?>
            </div>

        <?php endif; ?>
    </div>

</div>

<?php require __DIR__ . "/../footer.php"; ?>