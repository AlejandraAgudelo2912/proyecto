<?php
namespace App\Models;

use PDO;
use PDOException;
class Basedatos{
    
    private PDO | null $conexion;
    private string $motor;
    private string $host;
    private string $database;
    private string $user;
    private string $password;

    public function __construct(){
        $archivoConfig = __DIR__ . "/../config/config.json";

        $config = json_decode(file_get_contents($archivoConfig), true);

        $this->motor = $config["dbMotor"];
        $this->host = $config["mysqlHost"];
        $this->user = $config["mysqlUser"];
        $this->password = $config["mysqlPassword"];
        $this->database = $config["mysqlDatabase"];

        $DSN = "$this->motor:dbname=$this->database;host=$this->host;charset=utf8mb4";
        
        try{
            $this->conexion = new PDO($DSN, $this->user, $this->password);
            $this->conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        }
        catch (PDOException $e){
            $this->conexion = null;
            echo "Error de conexión a la base de datos: " . $e->getMessage();
            logger()->error("Error de conexión a la base de datos: " . $e->getMessage());
            die();
        }
    }

    public function getConexion(): PDO | null {
        return $this->conexion;
    }

    public function obtener_listado_Libros() {
        $pdo = $this->getConexion();
        $sql = "
            SELECT 
                libros.id,
                libros.titulo,
                libros.autor,
                libros.genero,
                libros.anio,
                libros.caratula,
                usuarios.nombre
                FROM libros
                INNER JOIN usuarios ON libros.id_usuario = usuarios.id
            ";
                
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $libros = $stmt->fetchAll(PDO::FETCH_ASSOC);
        logger()->info("Listado de libros obtenido correctamente");
        return $libros;
    }

    public function obtener_usuario_por_email($email) {
        $pdo = $this->getConexion();
        $sql = "SELECT * FROM usuarios WHERE email = :email LIMIT 1";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        return $usuario;
    }
    
    public function crearUsuario(string $nombre, $apellido1, $apellido2, string $email, string $password): bool {
        $pdo = $this->getConexion();

        $sql = "INSERT INTO usuarios (nombre,apellido1, apellido2, email, password) VALUES (:nombre, :apellido1, :apellido2, :email, :password)";
        
        $stmt = $pdo->prepare($sql);
        
        return $stmt->execute([
            'nombre' => $nombre,
            'apellido1' => $apellido1,
            'apellido2' => $apellido2,
            'email' => $email,
            'password' => $password
        ]);
    }

    public function existeUsuario(string $email): bool {
        $pdo = $this->getConexion();

        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = :email");
        $stmt->execute(['email' => $email]);

        return $stmt->fetch() !== false;
    }

    public function crearLibro($titulo, $autor, $genero, $anio, $id_usuario, $caratula): bool {
        $pdo = $this->getConexion();

        $sql = "INSERT INTO libros (titulo, id_usuario, autor, genero, anio, caratula) 
                VALUES (:titulo, :id_usuario, :autor, :genero, :anio, :caratula)";

        $stmt = $pdo->prepare($sql);

        return $stmt->execute([
            'titulo' => $titulo,
            'autor' => $autor,
            'genero' => $genero,
            'anio' => $anio,
            'id_usuario' => $id_usuario,
            'caratula' => $caratula
        ]);
    }

}
