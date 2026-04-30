<?php
namespace App\Models;

use App\Models\Basedatos;
use PDO;

class LibroModel {
    private $db;

    public function __construct() {
        $this->db = (new Basedatos())->getConexion();
    }

    public function obtener_listado_Libros($orden = '', $disponibilidad = '') {

        $sql = "
            SELECT 
                libros.id,
                libros.titulo,
                libros.autor,
                libros.genero,
                libros.anio,
                libros.caratula,
                libros.id_usuario,
                libros.prestado,
                libros.prestado_a,
                usuarios.nombre
                FROM libros
                INNER JOIN usuarios ON libros.id_usuario = usuarios.id
                WHERE 1=1
            ";

        if ($disponibilidad === 'disponible') {
            $sql .= " AND libros.prestado = 0";
        } elseif ($disponibilidad === 'prestado') {
            $sql .= " AND libros.prestado = 1";
        }     


        if ($orden === 'titulo') {
            $sql .= " ORDER BY libros.titulo ASC";
        } elseif ($orden === 'anio') {
            $sql .= " ORDER BY libros.anio DESC";
        }
     
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $libros = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $libros;
    }

    public function crearLibro($titulo, $autor, $genero, $anio, $id_usuario, $caratula) {

        $sql = "INSERT INTO libros (titulo, id_usuario, autor, genero, anio, caratula) 
                VALUES (:titulo, :id_usuario, :autor, :genero, :anio, :caratula)";

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            'titulo' => $titulo,
            'autor' => $autor,
            'genero' => $genero,
            'anio' => $anio,
            'id_usuario' => $id_usuario,
            'caratula' => $caratula
        ]);
        return $this->db->lastInsertId();
    }

    public function buscarLibros($busqueda, $orden = '', $disponibilidad = '') {

        $sql = "
            SELECT libros.*, usuarios.nombre
            FROM libros
            INNER JOIN usuarios ON libros.id_usuario = usuarios.id
            WHERE 
                (libros.titulo LIKE :busqueda OR
                libros.autor LIKE :busqueda OR
                libros.genero LIKE :busqueda OR
                libros.anio LIKE :busqueda OR
                usuarios.nombre LIKE :busqueda)
        ";
        if ($disponibilidad === 'disponible') {
            $sql .= " AND libros.prestado = 0";
        } elseif ($disponibilidad === 'prestado') {
            $sql .= " AND libros.prestado = 1";
        }

        if ($orden === 'titulo') {
            $sql .= " ORDER BY libros.titulo ASC";
        } elseif ($orden === 'anio') {
            $sql .= " ORDER BY libros.anio DESC";
        }

        $stmt = $this->db->prepare($sql);

        $stmt->execute([
            'busqueda' => "%" . $busqueda . "%"
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

        public function obtenerLibroPorId($id) {

        $sql = "
            SELECT libros.*, usuarios.nombre
            FROM libros
            INNER JOIN usuarios ON libros.id_usuario = usuarios.id
            WHERE libros.id = :id
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function eliminarLibro($id) {

        $sqlCheck = "SELECT COUNT(*) FROM prestamos WHERE id_libro = :id";
        $stmtCheck = $this->db->prepare($sqlCheck);
        $stmtCheck->execute(['id' => $id]);

        $tienePrestamos = $stmtCheck->fetchColumn();

        if ($tienePrestamos > 0) {
            return false;
        }

        $sql = "DELETE FROM libros WHERE id = :id";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute(['id' => $id]);
    }

    public function obtenerLibrosRecientes() {

        $sql = "
            SELECT libros.*, usuarios.nombre
            FROM libros
            INNER JOIN usuarios ON libros.id_usuario = usuarios.id
            ORDER BY libros.id DESC
            LIMIT 5
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerLibrosMejorValorados() {

        $sql = "
            SELECT libros.*, usuarios.nombre, AVG(valoraciones.puntuacion) AS valoracion_media
            FROM libros
            INNER JOIN usuarios ON libros.id_usuario = usuarios.id
            LEFT JOIN valoraciones ON libros.id = valoraciones.id_libro
            GROUP BY libros.id
            ORDER BY valoracion_media DESC
            LIMIT 5
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function actualizarLibroConImagen($id, $titulo, $autor, $genero, $anio, $caratula) {

        $sql = "UPDATE libros 
                SET titulo = :titulo,
                    autor = :autor,
                    genero = :genero,
                    anio = :anio,
                    caratula = :caratula
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'titulo' => $titulo,
            'autor' => $autor,
            'genero' => $genero,
            'anio' => $anio,
            'caratula' => $caratula,
            'id' => $id
        ]);
    }

    public function actualizarLibro($id, $titulo, $autor, $genero, $anio) {

        $sql = "UPDATE libros 
                SET titulo = :titulo,
                    autor = :autor,
                    genero = :genero,
                    anio = :anio
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'titulo' => $titulo,
            'autor' => $autor,
            'genero' => $genero,
            'anio' => $anio,
            'id' => $id
        ]);
    }

    public function obtenerGeneros() {

        $sql = "SELECT DISTINCT genero FROM libros ORDER BY genero ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function obtenerLibrosPorGenero($genero) {

        $sql = "SELECT * FROM libros WHERE genero = :genero";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['genero' => $genero]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}