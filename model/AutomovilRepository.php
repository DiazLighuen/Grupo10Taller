<?php

/**
 * Class LoginRepository
 */
class AutomovilRepository extends PDORepository{

    private static $instance;

    public static function getInstance(){

        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function __construct(){

    }

    public function buscar_automovil($fecha_desde,$fecha_hasta,$ciudad,$tipo_automovil,$precio){
      // para probar, tengo que hacer la consulta adecuada
      $con = $this->getConnection ();
      $sql = 'SELECT * FROM vehicle v, city c where v.city_id = c.id and (description = :description or :description = "")
      and (price = :price or :price = "") and (c.name = :city or :city = "") ORDER BY v.id DESC';

      $stmt = $con->prepare ( $sql );
      $stmt->bindParam (':description', $description, PDO::PARAM_STR );
      $stmt->bindParam (':city', $city, PDO::PARAM_STR );
      $stmt->bindParam (':price', $price, PDO::PARAM_INT );

      $description = filter_var(trim($tipo_automovil), FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
      $city = filter_var(trim($ciudad), FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
      $price = $precio;
      if (((int)$precio)>0){
          $price = (int) $precio;
      }

      $stmt->execute ();
      $vehiculos = $stmt->fetchAll ();
      return $vehiculos;
    }

    public function listar_tipos_automoviles(){
      // para probar, tengo que hacer la consulta adecuada
      $con = $this->getConnection ();
      $sql = 'SELECT DISTINCT (description) FROM vehicle ORDER BY description ASC';
      $stmt = $con->prepare ( $sql );
      $stmt->execute ();
      $tipos_automoviles = $stmt->fetchAll ();
      return $tipos_automoviles;
    }

    public function listar_precios(){
      // para probar, tengo que hacer la consulta adecuada
      $con = $this->getConnection ();
      $sql = 'SELECT DISTINCT (price) FROM vehicle ORDER BY price ASC';
      $stmt = $con->prepare ( $sql );
      $stmt->execute ();
      $precios = $stmt->fetchAll ();
      return $precios;
    }

}
