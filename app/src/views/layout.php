<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'Préstamo de Libros' ?></title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex flex-col">

    <?php require __DIR__ . "/partials/navbar.php"; ?>

    <main class="flex-1 w-full max-w-6xl mx-auto px-4 py-8">

        <div class="bg-white shadow-lg rounded-2xl p-6">

