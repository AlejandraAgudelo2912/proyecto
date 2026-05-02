<?php
session_start();
require __DIR__ . "/../../../vendor/autoload.php";
require __DIR__ . "/../layout.php";

use App\Models\UsuarioModel;

if (!isset($_SESSION["usuario"])) {
    header("Location: ../auth/login.php");
    exit;
}

$usuarioModel = new UsuarioModel();
$topLectores = $usuarioModel->obtenerTopLectores(10);
?>

<div class="max-w-4xl mx-auto">

    <h1 class="text-4xl font-bold text-gray-800 mb-2">🏆 Top lectores</h1>
    <p class="text-gray-500 mb-8">Los usuarios más activos de la comunidad</p>

    <?php if (empty($topLectores)): ?>
        <p class="text-gray-500">Aún no hay datos de lectores</p>
    <?php else: ?>

        <div class="space-y-4">

            <?php foreach ($topLectores as $i => $lector): ?>

                <?php
                    $posicion = $i + 1;
                    $badges = obtenerBadges(
                        (int)$lector['libros_subidos'],
                        (int)$lector['libros_leidos'],
                        (int)$lector['valoraciones']
                    );

                    // Estilos según posición
                    $medalColor = match($posicion) {
                        1 => 'from-yellow-400 to-amber-500',
                        2 => 'from-gray-300 to-gray-400',
                        3 => 'from-orange-300 to-orange-400',
                        default => 'from-gray-100 to-gray-200'
                    };

                    $medalEmoji = match($posicion) {
                        1 => '🥇',
                        2 => '🥈',
                        3 => '🥉',
                        default => '#' . $posicion
                    };

                    $cardBorder = match($posicion) {
                        1 => 'border-2 border-yellow-300',
                        2 => 'border-2 border-gray-300',
                        3 => 'border-2 border-orange-300',
                        default => ''
                    };

                    $esYo = ($lector['id'] == $_SESSION['usuario']['id']);
                ?>

                <div class="bg-white rounded-2xl shadow-md p-5 flex items-center gap-5 hover:shadow-lg transition <?= $cardBorder ?> <?= $esYo ? 'ring-2 ring-blue-400' : '' ?>">

                    <!-- Posición -->
                    <div class="w-12 h-12 rounded-full bg-gradient-to-br <?= $medalColor ?> flex items-center justify-center text-white font-bold text-lg shadow-sm flex-shrink-0">
                        <?= $medalEmoji ?>
                    </div>

                    <!-- Info -->
                    <div class="flex-1 min-w-0">

                        <div class="flex items-center gap-2 mb-1">
                            <h2 class="font-bold text-lg text-gray-800 truncate">
                                <?= htmlspecialchars($lector['nombre']) ?>
                            </h2>

                            <?php if ($esYo): ?>
                                <span class="text-xs bg-blue-100 text-blue-600 px-2 py-0.5 rounded-full">Tú</span>
                            <?php endif; ?>
                        </div>

                        <!-- Stats -->
                        <div class="flex gap-4 text-sm text-gray-500 mb-2">
                            <span>📕 <?= $lector['libros_leidos'] ?> leído<?= $lector['libros_leidos'] != 1 ? 's' : '' ?></span>
                            <span>📚 <?= $lector['libros_subidos'] ?> subido<?= $lector['libros_subidos'] != 1 ? 's' : '' ?></span>
                            <span>⭐ <?= $lector['valoraciones'] ?> valoracion<?= $lector['valoraciones'] != 1 ? 'es' : '' ?></span>
                        </div>

                        <!-- Badges -->
                        <?php if (!empty($badges)): ?>
                            <div class="flex flex-wrap gap-1">
                                <?php foreach ($badges as $badge): ?>
                                    <span class="inline-block <?= $badge['color'] ?> px-2 py-0.5 rounded-full text-xs">
                                        <?= $badge['emoji'] ?> <?= $badge['texto'] ?>
                                    </span>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

</div>

<?php require __DIR__ . "/../footer.php"; ?>
