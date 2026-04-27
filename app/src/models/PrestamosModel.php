<?php 

namespace App\Models;

use App\Models\Basedatos;
use PDO;

class PrestamosModel {
    private $db;

    public function __construct() {
        $this->db = (new Basedatos())->getConexion();
    }

    public function prestarLibro($idLibro, $usuarioId): bool {

        $sql = "UPDATE libros 
                SET prestado = 1, prestado_a = :usuario, fecha_prestamo = NOW()
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'usuario' => $usuarioId,
            'id' => $idLibro
        ]);
    }
    
    public function devolverLibro($idLibro, $usuarioId): bool {

        $sql = "UPDATE libros 
                SET prestado = 0, prestado_a = NULL, fecha_prestamo = NULL
                WHERE id = :id AND prestado_a = :usuario";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'usuario' => $usuarioId,
            'id' => $idLibro
        ]);
    }

    public function obtenerPrestamos() {

        $sql = "
            SELECT 
                libros.id,
                libros.titulo,
                libros.fecha_prestamo,
                u1.nombre AS propietario,
                u2.nombre AS prestado_a
            FROM libros
            INNER JOIN usuarios u1 ON libros.id_usuario = u1.id
            INNER JOIN usuarios u2 ON libros.prestado_a = u2.id
            WHERE libros.prestado = 1
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerLibrosPrestados($idUsuario) {

        $sql = "
            SELECT libros.*, usuarios.nombre
            FROM libros
            INNER JOIN usuarios ON libros.id_usuario = usuarios.id
            WHERE libros.prestado_a = :id
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $idUsuario]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}


