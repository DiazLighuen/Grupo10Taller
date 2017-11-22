<?php

/**
 * Class LoginRepository
 */
class BaseRepository extends PDORepository{

    private static $instance;

    public static function getInstance(){

        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function __construct(){

    }

    public function buscar_ciudades(){
      $con = $this->getConnection ();
      $sql = 'SELECT id, name FROM city ORDER BY name ASC';
      $stmt = $con->prepare ( $sql );
      $stmt->execute ();
      $ciudades = $stmt->fetchAll ();
      return $ciudades;

    }

}
