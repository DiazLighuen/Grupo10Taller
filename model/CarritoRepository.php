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
	
    public function buscar_carrito($usuario_id){
      $con = $this->getConnection ();
      $sql = 'SELECT cart_id FROM user_consumer where user_id = :usuario_id';
	  $stmt = $con->prepare ( $sql ); 
	  $stmt->bindParam (':usuario_id', $usuario_id, PDO::PARAM_INT );
      $stmt->execute ();
      return $stmt->fetchColumn();
    }	
	
	public function agregar_a_carrito_y_reservar($service_id, $type,$cart_id,$usuario_id,$fecha_desde,$fecha_hasta,$price){
		try{
			// agrego el servicio para el carrito del usuario logueado
			$con = $this->getConnection ();
			$sql = 'INSERT INTO services (cart_id, service_id, type) VALUES (:cart_id, :service_id, :type)';
			$stmt = $con->prepare ( $sql );
			$stmt->bindParam (':cart_id', $cart_id, PDO::PARAM_INT );
			$stmt->bindParam (':service_id', $service_id, PDO::PARAM_INT );
			$stmt->bindParam (':type', $type, PDO::PARAM_STR );
			$service_id = (int) $service_id;
			$cart_id = (int) $cart_id;
			$stmt->execute ();
			// genero la reserva para el servicio segun el tipo
			$id_reserva = $this->generar_reserva($service_id, $type,$usuario_id,$fecha_desde,$fecha_hasta,$price,$cart_id);
			return $id_reserva;
		}
		catch(Execption $e){
			$con->rollBack();
			return false;
		}
	}
	
	private function ajustar_precio_carrito($price,$cart_id,$fecha_desde,$fecha_hasta,$operacion,$type){
		$con = $this->getConnection ();
		if($operacion == 'suma'){
			$sql = 'UPDATE cart SET price = (price + :price) where id = :cart_id ';
		}
		else{
			$sql = 'UPDATE cart SET price = (price - :price) where id = :cart_id ';
		}
		$stmt = $con->prepare ( $sql );
		$stmt->bindParam (':price', $price, PDO::PARAM_INT );
		$stmt->bindParam (':cart_id', $cart_id, PDO::PARAM_INT );
		if(($type == 'vehicle' || $type == 'hotel') && $operacion == 'suma'){
			// para calcular el total 
			$datetime1 = date_create($fecha_desde);
			$datetime2 = date_create($fecha_hasta);
			$date_dif = date_diff($datetime1, $datetime2);
			$dias = $date_dif->d;
			$price = $price*$dias;
		}
		$stmt->execute ();	
	}
	
	public function generar_reserva($service_id, $type,$usuario_id,$fecha_desde,$fecha_hasta,$price,$cart_id){
		$resultado;
		switch ($type){
			case 'vehicle' :
				$con = $this->getConnection ();
				$sql = 'INSERT INTO vehicle_reserve (id_vehicle, id_user, date_in, date_out) VALUES (:id_vehicle, :id_user, :date_in, :date_out)';
				$stmt = $con->prepare ( $sql );
				$stmt->bindParam (':id_vehicle', $service_id, PDO::PARAM_INT );
				$stmt->bindParam (':id_user', $usuario_id, PDO::PARAM_INT );
				$stmt->bindParam (':date_in', $fecha_desde, PDO::PARAM_STR );
				$stmt->bindParam (':date_out', $fecha_hasta, PDO::PARAM_STR );
				$stmt->execute ();
				// recalculo en precio del carrito
				$this->ajustar_precio_carrito($price,$cart_id,$fecha_desde,$fecha_hasta,'suma',$type);
				$resultado = $con->lastInsertId();
			break;
			case 'flight' :
				$con = $this->getConnection ();
				$sql = 'UPDATE seat SET sell = 1 where id = :id_seat ';
				$stmt = $con->prepare ( $sql );
				$stmt->bindParam (':id_seat', $service_id, PDO::PARAM_INT );
				$stmt->execute ();
				// recalculo en precio del carrito
				$this->ajustar_precio_carrito($price,$cart_id,$fecha_desde,$fecha_hasta,'suma',$type);
				$resultado = true;
			break;
			case 'hotel' :
				$con = $this->getConnection ();
				$sql = 'INSERT INTO room_reserve (id_room, id_user, date_in, date_out) VALUES (:id_room, :id_user, :date_in, :date_out)';
				$stmt = $con->prepare ( $sql );
				$stmt->bindParam (':id_room', $service_id, PDO::PARAM_INT );
				$stmt->bindParam (':id_user', $usuario_id, PDO::PARAM_INT );
				$stmt->bindParam (':date_in', $fecha_desde);
				$stmt->bindParam (':date_out', $fecha_hasta);
				$stmt->execute ();
				// recalculo en precio del carrito
				$this->ajustar_precio_carrito($price,$cart_id,$fecha_desde,$fecha_hasta,'suma',$type);
				$resultado = $con->lastInsertId();
			break;
			default:
			//
			break;
		}
		return $resultado;
	}
	
     public function obtener_servicios_carrito(){
      $con = $this->getConnection ();
      $sql = 'SELECT s.* FROM user_consumer uc
				inner join cart ca on uc.cart_id = ca.id
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
		$sql = "SELECT :cart_id as cart_id, 'flight' as type, se.id as id_servicio, 
				concat(us.name, ' ', se.flight_id, ' ', se.number, ' ', co.name, ' ', cd.name, ' ', fl.origin_date, ' ', 
				se.class  ) as descripcion, price as total, null as id_serv
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
				$sql = "SELECT :cart_id as cart_id,'hotel' as type, r.id as id_servicio, concat(u.name) as descripcion, r.price * DATEDIFF( rr.date_out, rr.date_in ) as total, rr.id as id_serv
				FROM room_reserve rr
				inner join room r on rr.id_room = r.id
				inner join hotel h on r.hotel_id = h.id
				inner join hotel_company hc on h.hotel_company_id = hc.user_id
				inner join user u on u.id = hc.user_id
				where r.id = :service_id";
		}
		else
			if ($type == 'vehicle') {
				$sql = "SELECT :cart_id as cart_id, 'vehicle' as type, vi.id as id_servicio,
				concat(u.name , ' ' , vi.slots , ' ' , vi.fuel ,' ' , vi.description, ' $', vi.price, ' ', ci.name, ' ', vr.date_in,' ',
				vr.date_out) as descripcion, 
				vi.price * DATEDIFF( vr.date_out, vr.date_in )  as total,  vr.id id_serv
				FROM vehicle_reserve vr
				inner join vehicle vi on vr.id_vehicle = vi.id
				inner join concessionaire co on vi.concessionaire_id = co.concessionaire_id
				inner join user u on u.id = co.concessionaire_id
				inner join city ci on vi.city_id = ci.id
				where vi.id = :service_id";
			}
      $stmt = $con->prepare ( $sql );
      $c_id         = filter_var(trim($cart_id),         FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	  $stmt->bindParam ( ':cart_id', $c_id, PDO::PARAM_STR );
      $s_id         = filter_var(trim($service_id),         FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	  $stmt->bindParam ( ':service_id', $s_id, PDO::PARAM_STR );
      $stmt->execute ();
      $servicio_detalle = $stmt->fetch ();
      return $servicio_detalle;
    }
	
    public function eliminar_servicio_carrito($cart_id, $type, $service_id,$serv_id,$price,$fecha_desde,$fecha_hasta){
	  $con = $this->getConnection ();
      $sql = 'delete from services where cart_id = :cart_id and type = :type and service_id = :service_id';
      $stmt = $con->prepare ( $sql );
	  $stmt->bindParam (':cart_id', $cart_id, PDO::PARAM_INT );
	  $stmt->bindParam (':service_id', $service_id, PDO::PARAM_INT );
	  $stmt->bindParam (':type', $type, PDO::PARAM_STR );
	  $service_id = (int) $service_id;
	  $cart_id = (int) $cart_id;
      $stmt->execute();
	  
	  $elimino = $this->eliminar_reserva($type,$serv_id,$service_id,$cart_id,$price,$fecha_desde,$fecha_hasta);
      return $elimino;
    }	
	
	private function eliminar_reserva($type,$serv_id,$service_id,$cart_id,$price,$fecha_desde,$fecha_hasta){
		$resultado;
		switch ($type){
			case 'vehicle' :
				$con = $this->getConnection ();
				$sql = 'DELETE FROM vehicle_reserve where id = :serv_id';
				$stmt = $con->prepare ( $sql );
				$stmt->bindParam (':serv_id', $serv_id, PDO::PARAM_INT );
				$serv_id = (int) $serv_id;
				$stmt->execute ();
				$resultado = true;
				// recalculo en precio del carrito
				$this->ajustar_precio_carrito($price,$cart_id,$fecha_desde,$fecha_hasta,'resta',$type);
			break;
			case 'flight' :
				$con = $this->getConnection ();
				$sql = 'UPDATE seat SET sell = 0 where id = :id_seat ';
				$stmt = $con->prepare ( $sql );
				$stmt->bindParam (':id_seat', $service_id, PDO::PARAM_INT );
				$stmt->execute ();
				$resultado = true;
				// recalculo en precio del carrito
				$this->ajustar_precio_carrito($price,$cart_id,$fecha_desde,$fecha_hasta,'resta',$type);
			break;
			case 'hotel' :
				$con = $this->getConnection ();
				$sql = 'DELETE FROM room_reserve where id = :serv_id';
				$stmt = $con->prepare ( $sql );
				$stmt->bindParam (':serv_id', $serv_id, PDO::PARAM_INT );
				$serv_id = (int) $serv_id;
				$stmt->execute ();
				$resultado = true;
				// recalculo en precio del carrito
				$this->ajustar_precio_carrito($price,$cart_id,$fecha_desde,$fecha_hasta,'resta',$type);
			break;
			default:
			//
			break;
		}
		return $resultado;	
	}
    public function pago($params){
		$con = $this->getConnection ();
		$sql = 'insert into history (user_consumer_id, cart_id) values (:user_id, :cart_id)';
		$stmt = $con->prepare ( $sql );
		$stmt->bindParam (':cart_id', $cart_id, PDO::PARAM_INT );
		$stmt->bindParam (':user_id', $user_id, PDO::PARAM_INT );
		$cart_id = $params['cart_id'];
		$user_id = $params['user_id'];
		$stmt->execute();
		$this->crear_y_asociar_carrito($user_id);
    }
	private function crear_y_asociar_carrito($user_id){
		$carrito_id = $this->crear_carrito();
		
		$con = $this->getConnection ();
		$sql = 'update user_consumer set cart_id = :carrito_id where user_id = :user_id';
		$stmt = $con->prepare ( $sql );
		$stmt->bindParam (':user_id', $user_id, PDO::PARAM_INT );
		$stmt->bindParam (':carrito_id', $carrito_id, PDO::PARAM_INT );
		$stmt->execute();
	}	
}