<?php
session_start();

require __DIR__ . "/../vendor/autoload.php";

use App\Models\Basedatos;

$client = new Google_Client();

$client->setClientId($_ENV['GOOGLE_CLIENT_ID']);
$client->setClientSecret($_ENV['GOOGLE_CLIENT_SECRET']);
$client->setRedirectUri($_ENV['GOOGLE_REDIRECT_URI']);

$db = new Basedatos();

if (isset($_GET['code'])) {

    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $client->setAccessToken($token);

    $google_oauth = new Google_Service_Oauth2($client);
    $user = $google_oauth->userinfo->get();

    $email = $user->email;
    $nombre = $user->givenName ?? $user->name;
    $apellidos= explode(" ", $user->familyName ?? '');
    $apellido1 = $apellidos[0] ?? '';
    $apellido2 = $apellidos[1] ?? '';

    $usuario = $db->obtener_usuario_por_email($email);

    if (!$usuario) {
        $db->crearUsuario($nombre, $apellido1, $apellido2, $email, password_hash(uniqid(), PASSWORD_DEFAULT));
        $usuario = $db->obtener_usuario_por_email($email);
    }

    $_SESSION["usuario"] = $usuario;

    header("Location: index.php");
    exit;
}