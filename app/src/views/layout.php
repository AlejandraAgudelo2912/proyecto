<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="icon" type="image/svg+xml" href="/prestamo_de_libros/app/public/logo.svg">
    <meta charset="UTF-8">
    <title><?= $title ?? 'Préstamo de Libros' ?></title>

    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen flex flex-col bg-[#f5f3ef]">

    <!-- CONTENEDOR PRINCIPAL -->
    <div class="flex flex-1">

        <?php require __DIR__ . "/partials/sidebar.php"; ?>


        <!-- CONTENIDO -->
        <main class="flex-1 p-10">

