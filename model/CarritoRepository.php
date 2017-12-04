<?php

/**
 * Class LoginRepository
 */
class CarritoRepository extends PDORepository{

    private static $instance;

    public static function getInstance(){

        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function __construct(){

    }

    public function obtener_servicios_carrito(){
      // para probar, tengo que hacer la consulta adecuada
      $con = $this->getConnection ();
      $sql = 'SELECT s.* FROM user_consumer uc
				inner join services s on uc.cart_id = s.cart_id
				where user_id = :user_id';
      $stmt = $con->prepare ( $sql );
      $stmt->bindParam (':user_id', $_SESSION['id'], PDO::PARAM_INT );
      $stmt->execute ();
      $servicios = $stmt->fetchAll ();
      return $servicios;
    }

    public function obtener_servicio_detalle($cart_id,$type,$service_id){
      $con = $this->getConnection ();
	  if ($type == 'flight')
      $sql = "SELECT 'vuelo' as tipo, se.id, 
				concat(us.name, ' ', se.flight_id, ' ', se.number, ' ', co.name, ' ', cd.name, ' ', fl.origin_date, ' ', 
				se.class  ) as descripcion, price as total
				FROM seat se
				inner join flight fl on se.flight_id = fl.id 
				inner join airline ai on fl.airline_id = ai.user_id
				inner join user us on ai.user_id = us.id
				inner join city co on fl.origin_id = co.id
				inner join city cd on fl.destiny_id = cd.id
				where se.id = " || $service_id;
  	  elseif ($type == 'hotel')
      $sql = "SELECT 'hotel' as tipo, rr.id, '' as descripcion, 0 as total 
				FROM room_reserve rr
				inner join room r on rr.id_room = r.id
				inner join hotel h on r.hotel_id = h.id
				inner join hotel_company hc on h.hotel_company_id = hc.id
				where rr.id = " || $service_id;
  	  elseif ($type == 'vehicle')
      $sql = "SELECT 'vehículo' as tipo, vr.id, 
				concat(co.name , ' ' , vi.slots , ' ' , vi.fuel ,' ' , vi.description, ' $', vi.price, ' ', ci.name, ' ', vr.date_in,' ',
				vr.date_out, ' ', DATEDIFF( vr.date_out, vr.date_in )) as descripcion, 
				vi.price * DATEDIFF( vr.date_out, vr.date_in ) as total
				FROM vehicle_reserve vr
				inner join vehicle vi on vr.id_vehicle = vi.id
				inner join concessionaire co on vi.concessionaire_id = co.concessionaire_id
				inner join city ci on vi.city_id = ci.id
				where vr.id = " || $service_id;
      $stmt = $con->prepare ( $sql );
      $stmt->execute ();
      $servicio_detalle = $stmt->fetchAll ();
      return $servicio_detalle;
    }

}
