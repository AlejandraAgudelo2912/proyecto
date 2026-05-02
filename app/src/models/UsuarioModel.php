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

    /**
     * Cuenta los libros subidos por un usuario.
     */
    public function contarLibrosUsuario($id) {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM libros WHERE id_usuario = :id");
        $stmt->execute(['id' => $id]);
        return (int) $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    /**
     * Cuenta los libros que un usuario ha tomado prestados (devueltos = leídos).
     */
    public function contarLibrosLeidos($id) {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as total FROM prestamos 
            WHERE id_usuario = :id AND estado = 'devuelto'
        ");
        $stmt->execute(['id' => $id]);
        return (int) $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    /**
     * Cuenta las valoraciones hechas por un usuario.
     */
    public function contarValoraciones($id) {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM valoraciones WHERE id_usuario = :id");
        $stmt->execute(['id' => $id]);
        return (int) $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    /**
     * Obtiene el ranking de usuarios que más libros han leído (préstamos devueltos).
     */
    public function obtenerTopLectores($limit = 10) {
        $sql = "
            SELECT 
                u.id,
                u.nombre,
                u.email,
                COUNT(p.id) AS libros_leidos,
                (SELECT COUNT(*) FROM libros WHERE id_usuario = u.id) AS libros_subidos,
                (SELECT COUNT(*) FROM valoraciones WHERE id_usuario = u.id) AS valoraciones
            FROM usuarios u
            LEFT JOIN prestamos p ON u.id = p.id_usuario AND p.estado = 'devuelto'
            GROUP BY u.id
            ORDER BY libros_leidos DESC, libros_subidos DESC
            LIMIT :limit
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}