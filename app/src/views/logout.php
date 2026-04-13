<?php
require __DIR__ . "/../../vendor/autoload.php";

    session_start();
    session_destroy();

    //log
    logger()->info("Usuario ha cerrado sesión");

    header("Location: ../../public/index.php");
    exit;