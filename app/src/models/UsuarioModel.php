<?php
namespace App\Models;

use App\Models\Basedatos;
use PDO;

class UsuarioModel {
    private $db;

    public function __construct() {
        $this->db = (new Basedatos())->getConexion();
    }

    public function obtener_usuario_por_email($email) {
        $sql = "SELECT * FROM usuarios WHERE email = :email LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        return $usuario;
    }
    
    public function crearUsuario(string $nombre, $apellido1, $apellido2, string $email, string $password): bool {

        $apiKey = generarApiKey();

        $sql = "INSERT INTO usuarios 
                (nombre, apellido1, apellido2, email, password, api_key) 
                VALUES (:nombre, :apellido1, :apellido2, :email, :password, :api_key)";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'nombre' => $nombre,
            'apellido1' => $apellido1,
            'apellido2' => $apellido2,
            'email' => $email,
            'password' => $password,
            'api_key' => $apiKey
        ]);
    }

    public function existeUsuario(string $email): bool {

        $stmt = $this->db->prepare("SELECT id FROM usuarios WHERE email = :email");
        $stmt->execute(['email' => $email]);

        return $stmt->fetch() !== false;
    }

    public function obtenerUsuarios() {

        $sql = "SELECT id, nombre, email, rol FROM usuarios";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    
    public function eliminarUsuario($id) {
        $this->db->prepare("DELETE FROM libros WHERE id_usuario = :id")->execute(['id' => $id]);

        $stmt = $this->db->prepare("DELETE FROM usuarios WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function cambiarRol($id, $rol) {

        $stmt = $this->db->prepare("UPDATE usuarios SET rol = :rol WHERE id = :id");

        return $stmt->execute([
            'rol' => $rol,
            'id' => $id
        ]);
    }

    public function obtenerLibrosPropios($idUsuario) {

        $sql = "
            SELECT *
            FROM libros
            WHERE id_usuario = :id
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $idUsuario]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function usuarioTienePrestamos($id) {

        $sql = "
            SELECT COUNT(*) as total
            FROM libros
            WHERE id_usuario = :id OR prestado_a = :id
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);

        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        return $resultado['total'] > 0;
    }

}