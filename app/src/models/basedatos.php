<?php
namespace App\Models;

use PDO;
use PDOException;

class Basedatos{
    private PDO | null $conexion;

    public function __construct(){

        $archivoConfig = __DIR__ . "/../config/config.json";

        $config = json_decode(file_get_contents($archivoConfig), true);

        $motor = $config["dbMotor"];
        $host = $config["mysqlHost"];
        $user = $config["mysqlUser"];
        $password = $config["mysqlPassword"];
        $database = $config["mysqlDatabase"];

        $DSN = "$motor:dbname=$database;host=$host;charset=utf8mb4";
        
        try{
            $this->conexion = new PDO($DSN, $user, $password);
            $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        }
        catch (PDOException $e){
            $this->conexion = null;
            echo "Error de conexión a la base de datos: " . $e->getMessage();
            die();
        }
    }

    public function getConexion(): PDO | null {
        return $this->conexion;
    }
}
