<?php

$host = "localhost";
$bd   = "prestamos_de_libros"; 
$user = "root";            
$pass = "";               

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$bd;charset=utf8",
        $user,
        $pass
    );


    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
   
    die("Error al conectar con la base de datos");
}
