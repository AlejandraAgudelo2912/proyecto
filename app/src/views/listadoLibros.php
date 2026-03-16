<?php
session_start();
require __DIR__ . "/../../vendor/autoload.php";
use App\Models\Basedatos;

$basedatos = new Basedatos();
$pdo = $basedatos->getConexion();

$sql = "
SELECT 
    libros.id,
    libros.titulo,
    libros.autor,
    libros.genero,
    libros.anio,
    usuarios.nombre
    FROM libros
    INNER JOIN usuarios ON libros.id_usuario = usuarios.id
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
    </tr>

    <?php foreach ($libros as $libro): ?>
        <tr>
            <td><?= htmlspecialchars($libro['titulo']) ?></td>
            <td><?= htmlspecialchars($libro['autor']) ?></td>
            <td><?= htmlspecialchars($libro['genero']) ?></td>
            <td><?= htmlspecialchars($libro['anio']) ?></td>
           
        </tr>
    <?php endforeach; ?>

</table>

</body>
</html>
