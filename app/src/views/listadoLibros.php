<?php
session_start();
require __DIR__ . "/../vendor/autoload.php";
use App\Models\Basedatos;

$basedatos = new Basedatos();
    

$sql = "
SELECT 
    libros.id,
    libros.titulo,
    libros.autor,
    libros.genero,
    libros.año,
    usuarios.nombre AS dueno,
    prestamos.id AS prestado
FROM libros
JOIN usuarios ON libros.id_usuario = usuarios.id
LEFT JOIN prestamos ON libros.id = prestamos.id_libro
";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$libros = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de libros</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>

<h1>Libros disponibles</h1>

<table border="1" cellpadding="8">
    <tr>
        <th>Título</th>
        <th>Autor</th>
        <th>Género</th>
        <th>Año</th>
        <th>Dueño</th>
        <th>Estado</th>
    </tr>

    <?php foreach ($libros as $libro): ?>
        <tr>
            <td><?= htmlspecialchars($libro['titulo']) ?></td>
            <td><?= htmlspecialchars($libro['autor']) ?></td>
            <td><?= htmlspecialchars($libro['genero']) ?></td>
            <td><?= htmlspecialchars($libro['anio']) ?></td>
            <td><?= htmlspecialchars($libro['dueno']) ?></td>
            <td>
                <?php if ($libro['prestado']): ?>
                    Prestado
                <?php else: ?>
                    Disponible
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>

</table>

</body>
</html>
