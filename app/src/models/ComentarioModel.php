<?php
namespace App\Models;

use PDO;
use PDOException;

class ComentarioModel {
    private $db;

    public function __construct() {
        $this->db = (new Basedatos())->getConexion();
    }

    public function crearComentario($idLibro, $idUsuario, $texto) {

        $sql = "INSERT INTO comentarios (id_libro, id_usuario, comentario)
                VALUES (:libro, :usuario, :comentario)";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'libro' => $idLibro,
            'usuario' => $idUsuario,
            'comentario' => $texto
        ]);
    }


    public function obtenerComentarios($idLibro) {

        $sql = "
            SELECT comentarios.*, usuarios.nombre
            FROM comentarios
            INNER JOIN usuarios ON comentarios.id_usuario = usuarios.id
            WHERE id_libro = :id
            ORDER BY fecha DESC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $idLibro]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}