<?php
require __DIR__ . "/../vendor/autoload.php";

$client = new Google_Client();
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$client->setClientId($_ENV['GOOGLE_CLIENT_ID']);
$client->setClientSecret($_ENV['GOOGLE_CLIENT_SECRET']);
$client->setRedirectUri($_ENV['GOOGLE_REDIRECT_URI']);

$client->addScope("email");
$client->addScope("profile");

header("Location: " . $client->createAuthUrl());
exit;