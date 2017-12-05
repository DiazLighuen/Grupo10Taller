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

    public function buscar_hotel($fecha_desde,$fecha_hasta,$id_ciudad){
      $con = $this->getConnection ();
      $sql = 'SELECT r.id AS id_room, r.number, r.beds, r.description, r.price, h.address, h.quality, c.name as nombre_ciudad, s.name as nombre_pais, u.name as nombre_usuario FROM room r, hotel h, city c, state s, user u WHERE r.hotel_id = h.id AND h.city_id = c.id AND h.state_id = s.id AND h.hotel_company_id = u.id AND city_id = :id_ciudad and r.id not in (SELECT id_room FROM room_reserve WHERE (:fecha_desde BETWEEN date_in and date_out ) or (:fecha_hasta BETWEEN date_in and date_out))';
      $stmt = $con->prepare ( $sql );
      $stmt->bindParam(':id_ciudad', $id_ciudad);
      $stmt->bindParam(':fecha_desde', $fecha_desde);
      $stmt->bindParam(':fecha_hasta', $fecha_hasta);
      $stmt->execute ();
      $hoteles = $stmt->fetchAll ();

      return $hoteles;
    }

	public function hotel_show($id_room){
		$con = $this->getConnection ();
		$sql= 'SELECT r.id AS id_room, r.number, r.beds, r.description, r.price, h.address, h.quality, c.name as nombre_ciudad, s.name as nombre_pais, u.name as nombre_usuario FROM room r, hotel h, city c, state s, user u WHERE r.hotel_id = h.id AND h.city_id = c.id AND h.state_id = s.id AND h.hotel_company_id = u.id AND r.id = :id_room';
		$stmt = $con->prepare ( $sql );
		$stmt->bindParam (':id_room', $id_room, PDO::PARAM_INT );
		$id_room = (int) $id_room;
		$stmt->execute ();
		$room = $stmt->fetch ();
		return $room;	
	}
	
}
