<?php
session_start();
require __DIR__ . "/../../../vendor/autoload.php";

use App\Models\PrestamosModel;

$model = new PrestamosModel();

$solicitudes = $model->obtenerSolicitudesDeMisLibros($_SESSION['usuario']['id']);
?>

<?php include __DIR__ . "/../layout.php"; ?>

<div class="max-w-5xl mx-auto py-10">

    <h1 class="text-3xl font-bold mb-8 text-gray-800">
        📥 Solicitudes recibidas
    </h1>

    <?php if (empty($solicitudes)): ?>

        <div class="bg-gray-100 p-6 rounded-xl text-center text-gray-500 shadow">
            No tienes solicitudes 😴
        </div>

    <?php else: ?>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <?php foreach ($solicitudes as $s): ?>

                <div class="bg-white p-6 rounded-2xl shadow-md hover:shadow-xl transition-all duration-300">

                    <div class="mb-4">
                        <h2 class="text-lg font-semibold text-gray-800">
                            <?= htmlspecialchars($s['titulo']) ?>
                        </h2>

                        <p class="text-sm text-gray-500">
                            Solicita: 
                            <span class="font-medium text-gray-700">
                                <?= htmlspecialchars($s['solicitante']) ?>
                            </span>
                        </p>
                    </div>

                    <div class="flex gap-3">

                        <!-- ACEPTAR -->
                        <a href="<?= BASE_URL ?>src/views/libros/aceptarPrestamos.php?id=<?= $s['id'] ?>"
                           class="flex-1 text-center bg-green-500 text-white py-2 rounded-xl hover:bg-green-600 transition">
                            ✅ Aceptar
                        </a>

                        <!-- RECHAZAR -->
                        <a href="<?= BASE_URL ?>src/views/libros/rechazarPrestamos.php?id=<?= $s['id'] ?>"
                           class="flex-1 text-center bg-red-500 text-white py-2 rounded-xl hover:bg-red-600 transition">
                            ❌ Rechazar
                        </a>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

</div>

<?php require __DIR__ . "/../footer.php"; ?>