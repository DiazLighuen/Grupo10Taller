<?php

/**
 * Class LoginRepository
 */
class HotelRepository extends PDORepository{

    private static $instance;

    public static function getInstance(){

        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function __construct(){

    }

    public function buscar_hotel($fecha_desde,$fecha_hasta,$ciudad){
      // para probar que ande la conexion, tengo que hacer la consulta adecuada
      $con = $this->getConnection ();
      $sql = 'SELECT hotel.*, user.name FROM hotel INNER JOIN user ON hotel.hotel_company_id=user.id WHERE city_id= :city ORDER BY hotel.id DESC';
      $stmt = $con->prepare ( $sql );
      $stmt->bindParam(':city', $ciudad);
      $stmt->execute ();
      $hoteles = $stmt->fetchAll ();
      return $hoteles;
    }


}
