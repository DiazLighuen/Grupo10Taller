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

    public function buscar_vuelo($fecha_desde,$ciudad_origen_id,$ciudad_destino_id){
      $con = $this->getConnection ();
      $sql = 'SELECT s.id as id_seat, s.number, s.class, s.price, u.name as nombre_aerolinea, f.origin_date, f.destiny_date FROM seat s, flight f, user u where s.flight_id = f.id and u.id = f.airline_id and s.sell = 0 and f.origin_id = :ciudad_origen_id and f.destiny_id = :ciudad_destino_id and f.origin_date = :fecha_desde';
      $stmt = $con->prepare ( $sql );
      $stmt->bindParam (':ciudad_origen_id', $ciudad_origen_id, PDO::PARAM_INT);
	  $stmt->bindParam (':ciudad_destino_id', $ciudad_destino_id, PDO::PARAM_INT);
	  $stmt->bindParam (':fecha_desde', $fecha_desde, PDO::PARAM_INT );
	  $ciudad_origen_id = (int) $ciudad_origen_id;
	  $ciudad_destino_id = (int) $ciudad_destino_id;

      $stmt->execute ();
      $vuelos = $stmt->fetchAll ();
      return $vuelos;
    }

	public function vuelo_show($id_seat){
      $con = $this->getConnection ();
      $sql = 'SELECT s.id as id_seat, s.number, s.class, s.price, u.username as nombre_aerolinea, f.origin_date, f.destiny_date FROM seat s, flight f, user u where s.flight_id = f.id and u.id = f.airline_id and s.id = :id_seat';
      $stmt = $con->prepare ( $sql );
      $stmt->bindParam (':id_seat', $id_seat, PDO::PARAM_INT);
      $stmt->execute ();
      $vuelos = $stmt->fetch ();
      return $vuelos;
    }
	
}
