<?php
session_start();
require __DIR__ . "/../layout.php";
require __DIR__ . "/../../../vendor/autoload.php";

use App\Models\UsuarioModel;
use App\Models\LibroModel;
use App\Models\PrestamosModel;

if (!isset($_SESSION["usuario"]) || $_SESSION["usuario"]["rol"] !== 'admin') {
    die("Acceso denegado. Solo los administradores pueden acceder a esta página.");
}
$_SESSION['error'] = $_SESSION['error'] ?? null;

$usuarioModel = new UsuarioModel();
$libroModel = new LibroModel();
$prestamoModel = new PrestamosModel();

$usuarios = $usuarioModel->obtenerUsuarios();
$libros = $libroModel->obtener_listado_Libros();
$prestamos = $prestamoModel->obtenerPrestamos();
?>

<div class="max-w-7xl mx-auto">

    <h1 class="text-4xl font-bold mb-10">
        Panel de Administración
    </h1>

    <!-- STATS -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">

        <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-6 rounded-2xl shadow">
            <p class="text-sm opacity-80">Usuarios</p>
            <p class="text-3xl font-bold"><?= count($usuarios) ?></p>
        </div>

        <div class="bg-gradient-to-r from-purple-500 to-purple-600 text-white p-6 rounded-2xl shadow">
            <p class="text-sm opacity-80">Libros</p>
            <p class="text-3xl font-bold"><?= count($libros) ?></p>
        </div>

        <div class="bg-gradient-to-r from-green-500 to-green-600 text-white p-6 rounded-2xl shadow">
            <p class="text-sm opacity-80">Prestados</p>
            <p class="text-3xl font-bold">
                <?= array_sum(array_column($libros, 'prestado')) ?>
            </p>
        </div>

    </div>

    <!-- ERROR -->
    <?php if (isset($_SESSION['error'])): ?>
        <div class="bg-red-100 text-red-600 p-4 rounded mb-6">
            <?= $_SESSION['error'] ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>


    <!-- TABS -->
    <div class="flex gap-4 mb-6">
        <button id="btnUsuarios" onclick="mostrar('usuarios')" class="px-4 py-2 bg-blue-600 text-white rounded-lg">
            Usuarios
        </button>
        <button id="btnLibros" onclick="mostrar('libros')" class="px-4 py-2 bg-gray-200 rounded-lg">
            Libros
        </button>

        <button id="btnPrestamos" onclick="mostrar('prestamos')" class="px-4 py-2 bg-gray-200 rounded-lg" id="btnPrestamos">
            Préstamos
        </button>
    </div>

    <!-- USUARIOS -->
    <div id="usuarios">

        <div class="bg-white rounded-2xl shadow overflow-hidden">

            <table class="w-full text-sm">
                <thead class="bg-gray-100 text-gray-600">
                    <tr>
                        <th class="p-3 text-left">ID</th>
                        <th class="p-3 text-left">Nombre</th>
                        <th class="p-3 text-left">Email</th>
                        <th class="p-3 text-left">Rol</th>
                        <th class="p-3 text-left">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($usuarios as $usuario) : ?>
                        <tr class="border-t hover:bg-gray-50 transition">

                            <td class="p-3"><?= $usuario["id"] ?></td>
                            <td class="p-3 font-medium"><?= $usuario["nombre"] ?></td>
                            <td class="p-3 text-gray-500"><?= $usuario["email"] ?></td>

                            <td class="p-3">

                                <form action="cambiarRol.php" method="POST">

                                    <input type="hidden" name="id" value="<?= $usuario['id'] ?>">

                                    <select name="rol"
                                        onchange="this.form.submit()"
                                        class="border rounded px-2 py-1 text-sm 
                                            <?= $usuario['rol']=='admin' ? 'bg-red-100 text-red-600' : 'bg-gray-100' ?>">

                                        <option value="user" <?= $usuario['rol']=='user'?'selected':'' ?>>
                                            user
                                        </option>

                                        <option value="admin" <?= $usuario['rol']=='admin'?'selected':'' ?>>
                                            admin
                                        </option>

                                    </select>

                                </form>

                            </td>

                            <td class="p-3">
                                <a href="eliminarUsuario.php?id=<?= $usuario['id'] ?>"
                                   class="text-red-500 hover:underline text-sm"
                                onclick="return confirm('¿Seguro que quieres eliminar esto?')">
                                    Dar de baja
                                </a>
                            </td>

                        </tr>
                    <?php endforeach; ?>
                </tbody>

            </table>

        </div>

    </div>

    <!-- LIBROS -->
    <div id="libros" style="display:none;">

        <div class="bg-white rounded-2xl shadow overflow-hidden">

            <table class="w-full text-sm">
                <thead class="bg-gray-100 text-gray-600">
                    <tr>
                        <th class="p-3 text-left">ID</th>
                        <th class="p-3 text-left">Título</th>
                        <th class="p-3 text-left">Autor</th>
                        <th class="p-3 text-left">Año</th>
                        <th class="p-3 text-left">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($libros as $libro) : ?>
                        <tr class="border-t hover:bg-gray-50 transition">

                            <td class="p-3"><?= $libro["id"] ?></td>
                            <td class="p-3 font-medium">
                                <a href="../libros/verLibro.php?id=<?= $libro['id'] ?>"
                                   class="hover:underline text-blue-600">
                                    <?= $libro["titulo"] ?>
                                </a>
                            </td>
                            <td class="p-3 text-gray-500"><?= $libro["autor"] ?></td>
                            <td class="p-3"><?= $libro["anio"] ?></td>

                            <td class="p-3">
                                <a href="eliminarLibro.php?id=<?= $libro['id'] ?>"
                                   class="text-red-500 hover:underline text-sm"
                                    onclick="return confirm('¿Seguro que quieres eliminar esto?')">
                                    Eliminar
                                </a>
                            </td>

                        </tr>
                    <?php endforeach; ?>
                </tbody>

            </table>

        </div>

    </div>

    <!-- PRESTAMOS -->
    <div id="prestamos" style="display:none;">

        <div class="bg-white rounded-2xl shadow overflow-hidden">

            <table class="w-full text-sm">
                <thead class="bg-gray-100 text-gray-600">
                    <tr>
                        <th class="p-3 text-left">Libro</th>
                        <th class="p-3 text-left">Propietario</th>
                        <th class="p-3 text-left">Prestado a</th>
                        <th class="p-3 text-left">Fecha</th>
                        <th class="p-3 text-left">Días</th>
                    </tr>
                </thead>

                <tbody>
                    <?php foreach ($prestamos as $p): ?>

                        <?php
                            $dias = null;
                            if ($p['fecha_prestamo']) {
                                $inicio = new DateTime($p['fecha_prestamo']);
                                $hoy = new DateTime();
                                $dias = $hoy->diff($inicio)->days;
                            }
                        ?>

                        <tr class="border-t hover:bg-gray-50">

                            <td class="p-3 font-medium">
                                <a href="../libros/verLibro.php?id=<?= $p["id"]?>"
                                   class="hover:underline text-blue-600">
                                    <?= $p['titulo'] ?>
                                </a>
                            </td>
                            <td class="p-3"><?= $p['propietario'] ?></td>
                            <td class="p-3"><?= $p['prestado_a'] ?></td>
                            <td class="p-3"><?= $p['fecha_prestamo'] ?></td>

                            <td class="p-3">
                                <span class="text-sm 
                                    <?= $dias > 14 ? 'text-red-500' : ($dias > 7 ? 'text-yellow-500' : 'text-green-500') ?>">
                                    <?= $dias ?> días
                                </span>
                            </td>

                        </tr>

                    <?php endforeach; ?>
                </tbody>

            </table>

        </div>

    </div>

</div>

<script>
function mostrar(seccion) {

    document.getElementById("usuarios").style.display = "none";
    document.getElementById("libros").style.display = "none";
    document.getElementById("prestamos").style.display = "none";

    document.getElementById(seccion).style.display = "block";

    const botones = ["btnUsuarios", "btnLibros", "btnPrestamos"];

    botones.forEach(id => {
        const btn = document.getElementById(id);
        btn.classList.remove("bg-blue-600", "text-white");
        btn.classList.add("bg-gray-200");
    });

    const activo = {
        usuarios: "btnUsuarios",
        libros: "btnLibros",
        prestamos: "btnPrestamos"
    };

    const btnActivo = document.getElementById(activo[seccion]);
    btnActivo.classList.remove("bg-gray-200");
    btnActivo.classList.add("bg-blue-600", "text-white");
}
</script>

<?php require __DIR__ . "/../footer.php"; ?>