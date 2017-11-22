<?php

/**
 * Class LoginRepository
 */
class VueloRepository extends PDORepository{

    private static $instance;

    public static function getInstance(){

        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function __construct(){

    }

    public function buscar_vuelo($fecha_desde,$fecha_hasta,$ciudad_origen,$ciudad_destino){
      // para probar, tengo que hacer la consulta adecuada
      $con = $this->getConnection ();
      $sql = 'SELECT * FROM flight where origin_id = 1 ORDER BY id DESC';
      /*
      $sql = 'SELECT usu.username, usu.first_name, usu.last_name, usu.email, usu.activo FROM usuario usu where (usu.username = :nombre
        or :nombre = "") and (usu.activo = :activo or :activo = "") ORDER BY usu.id DESC LIMIT :empezar_desde, :cantidad_resultados_por_pagina';
        */
      $stmt = $con->prepare ( $sql );
      /*
      $stmt->bindParam (':nombre', $nombre_usuario, PDO::PARAM_STR );
      $stmt->bindParam (':activo', $estado);
      $stmt->bindParam ( ':empezar_desde', $empezar_desde, PDO::PARAM_INT );
      */
      $stmt->execute ();
      $hoteles = $stmt->fetchAll ();
      return $hoteles;
    }


}
