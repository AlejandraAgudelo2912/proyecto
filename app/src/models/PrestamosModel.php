<?php 

namespace App\Models;

use App\Models\Basedatos;
use PDO;

class PrestamosModel {
    private $db;

    public function __construct() {
        $this->db = (new Basedatos())->getConexion();
    }

    public function solicitarPrestamo($idLibro, $usuarioId): bool {

        $sql = "SELECT id_usuario FROM libros WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $idLibro]);

        $libro = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$libro || $libro['id_usuario'] == $usuarioId) {
            return false;
        }

        //evitar duplicado
        $sqlPendiente = "SELECT id FROM prestamos 
                        WHERE id_libro = :id AND estado = 'pendiente'";
        $stmtPendiente = $this->db->prepare($sqlPendiente);
        $stmtPendiente->execute(['id' => $idLibro]);
        if ($stmtPendiente->fetch()) {
            return false;
        }
   
        $sqlPrestado = "SELECT id FROM prestamos 
                        WHERE id_libro = :id AND estado = 'aceptado'";
        $stmtPrestado = $this->db->prepare($sqlPrestado);
        $stmtPrestado->execute(['id' => $idLibro]);

        if ($stmtPrestado->fetch()) {
            return false;
        }
        
        $sqlInsert = "INSERT INTO prestamos (id_libro, id_usuario, estado, fecha_prestamo)
                    VALUES (:libro, :usuario, 'pendiente', CURDATE())";

        $stmtInsert = $this->db->prepare($sqlInsert);

        return $stmtInsert->execute([
            'libro' => $idLibro,
            'usuario' => $usuarioId
        ]);
    }

    public function aceptarPrestamo($prestamoId): bool {

        $sql = "SELECT * FROM prestamos WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $prestamoId]);

        $prestamo = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$prestamo) return false;

        $sqlCheck = "SELECT prestado FROM libros WHERE id = :id";
        $stmtCheck = $this->db->prepare($sqlCheck);
        $stmtCheck->execute(['id' => $prestamo['id_libro']]);

        $libro = $stmtCheck->fetch(PDO::FETCH_ASSOC);

        if ($libro['prestado'] == 1) return false;

        $sqlUpdatePrestamo = "
            UPDATE prestamos 
            SET estado = 'aceptado',
                fecha_prestamo = CURDATE(),
                fecha_devolucion = DATE_ADD(CURDATE(), INTERVAL 14 DAY)
            WHERE id = :id
        ";

        $stmtUpdate = $this->db->prepare($sqlUpdatePrestamo);
        $stmtUpdate->execute(['id' => $prestamoId]);

        $sqlLibro = "
            UPDATE libros 
            SET prestado = 1,
                prestado_a = :usuario,
                fecha_prestamo = CURDATE()
            WHERE id = :libro
        ";

        $stmtLibro = $this->db->prepare($sqlLibro);

        return $stmtLibro->execute([
            'usuario' => $prestamo['id_usuario'],
            'libro' => $prestamo['id_libro']
        ]);
    }
    
    public function devolverLibro($idLibro, $usuarioId): bool {

        $sql = "UPDATE libros 
                SET prestado = 0, prestado_a = NULL, fecha_prestamo = NULL
                WHERE id = :id AND prestado_a = :usuario";

        $sqlPrestamo = "
                UPDATE prestamos 
                SET estado = 'devuelto'
                WHERE id_libro = :id 
                AND id_usuario = :usuario 
                AND estado = 'aceptado'";

        $stmtPrestamo = $this->db->prepare($sqlPrestamo);

        $stmtPrestamo->execute([
            'id' => $idLibro,
            'usuario' => $usuarioId
        ]);

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

    public function rechazarPrestamo($prestamoId): bool {

        $sql = "UPDATE prestamos SET estado = 'rechazado' WHERE id = :id";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute(['id' => $prestamoId]);
    }


    public function obtenerSolicitudesDeMisLibros($usuarioId) {

        $sql = "
            SELECT 
                p.id,
                l.titulo,
                l.id AS id_libro,
                u.nombre AS solicitante
            FROM prestamos p
            INNER JOIN libros l ON p.id_libro = l.id
            INNER JOIN usuarios u ON p.id_usuario = u.id
            WHERE l.id_usuario = :usuario
            AND p.estado = 'pendiente'
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['usuario' => $usuarioId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerSolicitudUsuario($idLibro, $idUsuario) {

        $sql = "SELECT estado FROM prestamos 
                WHERE id_libro = :libro 
                AND id_usuario = :usuario
                ORDER BY id DESC LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'libro' => $idLibro,
            'usuario' => $idUsuario
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerSolicitudesPendientes($usuarioId) {

        $sql = "
            SELECT l.*, u.nombre
            FROM prestamos p
            INNER JOIN libros l ON p.id_libro = l.id
            INNER JOIN usuarios u ON l.id_usuario = u.id
            WHERE p.id_usuario = :usuario
            AND p.estado = 'pendiente'
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['usuario' => $usuarioId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerSolicitudesAceptadas($usuarioId) {

        $sql = "
            SELECT l.*, u.nombre
            FROM prestamos p
            INNER JOIN libros l ON p.id_libro = l.id
            INNER JOIN usuarios u ON l.id_usuario = u.id
            WHERE p.id_usuario = :usuario
            AND p.estado = 'aceptado'
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['usuario' => $usuarioId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerSolicitudesRechazadas($usuarioId) {

        $sql = "
            SELECT l.*, u.nombre
            FROM prestamos p
            INNER JOIN libros l ON p.id_libro = l.id
            INNER JOIN usuarios u ON l.id_usuario = u.id
            WHERE p.id_usuario = :usuario
            AND p.estado = 'rechazado'
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['usuario' => $usuarioId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerPrestamosPorEstado($estado) {

        $sql = "
            SELECT 
                p.*,
                l.titulo,
                u1.nombre AS propietario,
                u2.nombre AS solicitante
            FROM prestamos p
            INNER JOIN libros l ON p.id_libro = l.id
            INNER JOIN usuarios u1 ON l.id_usuario = u1.id
            INNER JOIN usuarios u2 ON p.id_usuario = u2.id
            WHERE p.estado = :estado
            ORDER BY p.fecha_prestamo DESC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['estado' => $estado]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}


