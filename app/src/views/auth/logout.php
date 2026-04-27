<?php
require __DIR__ . "/../../../vendor/autoload.php";

    session_start();

    //indicamos en el log que el usuario ha cerrado sesión
    logger()->info("Usuario ha cerrado sesión: " . ($_SESSION["usuario"]["email"] ?? "desconocido"));

    session_destroy();

    header("Location: ../../../public/index.php");
    exit;