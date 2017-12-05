<?php

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

    public function crear_carrito(){
      $con = $this->getConnection ();
      $sql = 'INSERT INTO cart (date, price) VALUES (NOW(), 0)';
      $stmt = $con->prepare ( $sql );
      $stmt->execute ();
      return $con->lastInsertId();
    }
	
	public function agregar_a_carrito($service_id, $type, $cart_id){
	  $con = $this->getConnection ();
      $sql = 'INSERT INTO services (cart_id, service_id, type) VALUES (:cart_id, :service_id, :type)';
      $stmt = $con->prepare ( $sql );
	  $stmt->bindParam (':cart_id', $cart_id, PDO::PARAM_INT );
	  $stmt->bindParam (':service_id', $service_id, PDO::PARAM_INT );
	  $stmt->bindParam (':type', $type, PDO::PARAM_STR );
	  $service_id = (int) $service_id;
	  $cart_id = (int) $cart_id;
      $stmt->execute ();
	  echo var_dump($service_id);
	  echo var_dump($type);
	  echo var_dump($cart_id);
	  
      return $con->lastInsertId();
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

    public function obtener_servicio_detalle($cart_id, $type, $service_id){
	  $con = $this->getConnection ();
	  if ($type == 'flight') {
		$sql = "SELECT :cart_id as cart_id, 'flight' as type, se.id, 
				concat(us.name, ' ', se.flight_id, ' ', se.number, ' ', co.name, ' ', cd.name, ' ', fl.origin_date, ' ', 
				se.class  ) as descripcion, price as total
				FROM seat se
				inner join flight fl on se.flight_id = fl.id 
				inner join airline ai on fl.airline_id = ai.user_id
				inner join user us on ai.user_id = us.id
				inner join city co on fl.origin_id = co.id
				inner join city cd on fl.destiny_id = cd.id
				where se.id = :service_id";
	  }
  	  else
		if ($type == 'hotel') {
				$sql = "SELECT :cart_id as cart_id, 'hotel' as type, rr.id, '' as descripcion, 0 as total 
				FROM room_reserve rr
				inner join room r on rr.id_room = r.id
				inner join hotel h on r.hotel_id = h.id
				inner join hotel_company hc on h.hotel_company_id = hc.id
				where se.id = :service_id";
		}
		else
			if ($type == 'vehicle') {
				$sql = "SELECT :cart_id as cart_id, 'vehicle' as type, vr.id, 
				concat(co.name , ' ' , vi.slots , ' ' , vi.fuel ,' ' , vi.description, ' $', vi.price, ' ', ci.name, ' ', vr.date_in,' ',
				vr.date_out, ' ', DATEDIFF( vr.date_out, vr.date_in )) as descripcion, 
				vi.price * DATEDIFF( vr.date_out, vr.date_in ) as total
				FROM vehicle_reserve vr
				inner join vehicle vi on vr.id_vehicle = vi.id
				inner join concessionaire co on vi.concessionaire_id = co.concessionaire_id
				inner join city ci on vi.city_id = ci.id
				where se.id = :service_id";
			}
			//else 
			//	$sql = "SELECT '' as type, o as id, ' '  as descripcion, 0 as total";
      $stmt = $con->prepare ( $sql );
      $c_id         = filter_var(trim($cart_id),         FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	  $stmt->bindParam ( ':cart_id', $c_id, PDO::PARAM_STR );
      $s_id         = filter_var(trim($service_id),         FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	  $stmt->bindParam ( ':service_id', $s_id, PDO::PARAM_STR );
      $stmt->execute ();
      $servicio_detalle = $stmt->fetchAll ();
      return $servicio_detalle;
    }
	
    public function eliminar_servicio_carrito($cart_id, $type, $service_id){
		//var_dump($cart_id);
		//var_dump($type); 
		//var_dump($service_id);
	  $con = $this->getConnection ();
      $sql = 'delete from services where cart_id = :cart_id and type = :type and service_id = :service_id';
      $stmt = $con->prepare ( $sql );
	  $stmt->bindParam (':cart_id', $cart_id, PDO::PARAM_INT );
	  $stmt->bindParam (':service_id', $service_id, PDO::PARAM_INT );
	  $stmt->bindParam (':type', $type, PDO::PARAM_STR );
	  $service_id = (int) $service_id;
	  $cart_id = (int) $cart_id;
	  //var_dump($sql);
      $stmt->execute ();
      return ;
    }	

}
