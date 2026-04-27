<?php
namespace App\Models;

use App\Models\Basedatos;
use PDO;

class LibroModel {
    private $db;

    public function __construct() {
        $this->db = (new Basedatos())->getConexion();
    }

    public function obtener_listado_Libros() {

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
            ";
                
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $libros = $stmt->fetchAll(PDO::FETCH_ASSOC);
        logger()->info("Listado de libros obtenido correctamente");
        return $libros;
    }

    public function crearLibro($titulo, $autor, $genero, $anio, $id_usuario, $caratula): bool {

        $sql = "INSERT INTO libros (titulo, id_usuario, autor, genero, anio, caratula) 
                VALUES (:titulo, :id_usuario, :autor, :genero, :anio, :caratula)";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'titulo' => $titulo,
            'autor' => $autor,
            'genero' => $genero,
            'anio' => $anio,
            'id_usuario' => $id_usuario,
            'caratula' => $caratula
        ]);
    }

    public function buscarLibros($busqueda) {

        $sql = "
            SELECT libros.*, usuarios.nombre
            FROM libros
            INNER JOIN usuarios ON libros.id_usuario = usuarios.id
            WHERE 
                libros.titulo LIKE :busqueda OR
                libros.autor LIKE :busqueda OR
                libros.genero LIKE :busqueda OR
                libros.anio LIKE :busqueda OR
                usuarios.nombre LIKE :busqueda
        ";

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

        $stmt = $this->db->prepare("DELETE FROM libros WHERE id = :id");
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
}