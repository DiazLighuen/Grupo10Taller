<?php

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

    public function buscar_automovil($fecha_desde,$fecha_hasta,$ciudad,$precio){
    
		$con = $this->getConnection ();
		$sql= 'SELECT v.id, slots, fuel, description, price, c.name AS ciudad_name, u.name AS concessionaire_name FROM vehicle v, city c, user u WHERE city_id = c.id and u.id = v.concessionaire_id and price = :price and c.name = :city and v.id not in (SELECT id_vehicle FROM vehicle_reserve WHERE (:fecha_desde BETWEEN date_in and date_out ) or (:fecha_hasta BETWEEN date_in and date_out))';
		
		$stmt = $con->prepare ( $sql );
		$stmt->bindParam (':city', $city, PDO::PARAM_STR );
		$stmt->bindParam (':price', $precio, PDO::PARAM_INT );
		$stmt->bindParam (':fecha_desde', $fecha_desde, PDO::PARAM_INT );
		$stmt->bindParam (':fecha_hasta', $fecha_hasta, PDO::PARAM_INT );
		$city = filter_var(trim($ciudad), FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		$stmt->execute ();
		$vehiculos = $stmt->fetchAll ();

		return $vehiculos;
    }

    public function listar_tipos_automoviles(){
      $con = $this->getConnection ();
      $sql = 'SELECT DISTINCT (description) FROM vehicle ORDER BY description ASC';
      $stmt = $con->prepare ( $sql );
      $stmt->execute ();
      $tipos_automoviles = $stmt->fetchAll ();
      return $tipos_automoviles;
    }

    public function listar_precios(){
      $con = $this->getConnection ();
      $sql = 'SELECT DISTINCT (price) FROM vehicle ORDER BY price ASC';
      $stmt = $con->prepare ( $sql );
      $stmt->execute ();
      $precios = $stmt->fetchAll ();
      return $precios;
    }
	
	public function automovil_show($id_vehicle){
		
		$con = $this->getConnection ();
		$sql= 'SELECT v.id, slots, fuel, description, price, c.name AS ciudad_name, u.name AS concessionaire_name FROM vehicle v, city c, user u WHERE city_id = c.id and u.id = v.concessionaire_id and v.id = :id_vehicle';
		$stmt = $con->prepare ( $sql );
		$stmt->bindParam (':id_vehicle', $id_vehicle, PDO::PARAM_INT );
		$id_vehicle = (int) $id_vehicle;
		$stmt->execute ();
		$vehiculo = $stmt->fetch ();
		return $vehiculo;	
	}

}
