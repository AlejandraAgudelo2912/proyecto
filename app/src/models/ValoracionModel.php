<?php

namespace App\Models;

use PDO;
use App\Models\Basedatos;

class ValoracionModel {
    private $db;

    public function __construct() {
        $this->db = (new Basedatos())->getConexion();
    }

    public function obtenerMediaValoraciones($idLibro) {

        $stmt = $this->db->prepare("
            SELECT AVG(puntuacion) as media
            FROM valoraciones
            WHERE id_libro = :id
        ");

        $stmt->execute(['id' => $idLibro]);

        return round($stmt->fetch()['media'], 1);
    }

    public function valorarLibro($idLibro, $idUsuario, $puntuacion, $comentario) {

        $sql = "
            INSERT INTO valoraciones (id_libro, id_usuario, puntuacion, comentario)
            VALUES (:libro, :usuario, :puntuacion, :comentario)
            ON DUPLICATE KEY UPDATE
                puntuacion = :puntuacion,
                comentario = :comentario
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'libro' => $idLibro,
            'usuario' => $idUsuario,
            'puntuacion' => $puntuacion,
            'comentario' => $comentario ?? ''
        ]);
    }

    public function obtenerValoraciones($idLibro) {

        $sql = "
            SELECT valoraciones.*, usuarios.nombre
            FROM valoraciones
            INNER JOIN usuarios ON valoraciones.id_usuario = usuarios.id
            WHERE id_libro = :id
            ORDER BY fecha DESC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $idLibro]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtenerValoracionUsuario($idLibro, $idUsuario) {

        $stmt = $this->db->prepare("
            SELECT * FROM valoraciones
            WHERE id_libro = :libro AND id_usuario = :usuario
        ");

        $stmt->execute([
            'libro' => $idLibro,
            'usuario' => $idUsuario
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function editarValoracion($idLibro, $idUsuario, $puntuacion, $comentario) {

        $sql = "
            UPDATE valoraciones
            SET puntuacion = :puntuacion, comentario = :comentario, fecha = NOW()
            WHERE id_libro = :libro AND id_usuario = :usuario
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'libro' => $idLibro,
            'usuario' => $idUsuario,
            'puntuacion' => $puntuacion,
            'comentario' => $comentario ?? ''
        ]);
    }


}