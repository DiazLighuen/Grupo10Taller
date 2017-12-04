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

}
