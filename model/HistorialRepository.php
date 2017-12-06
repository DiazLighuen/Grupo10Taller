<?php

class HistorialRepository extends PDORepository{

    private static $instance;

    public static function getInstance(){

        if (!isset(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function __construct(){

    }


    public function obtener_carritos($id){
        $con = $this->getConnection ();
        $sql = 'SELECT c.date, c.price, c.id
                FROM cart AS c 
                INNER JOIN history AS h ON c.id = h.cart_id 
                WHERE h.user_consumer_id = :user_id';
        $stmt = $con->prepare ( $sql );
        $stmt->bindParam (':user_id', $id, PDO::PARAM_INT );
        $stmt->execute ();
        $rows = $stmt->fetchAll ();
        return $rows;
    }

    public function obtener_vuelos($id){
        $con = $this->getConnection ();
        $sql = "SELECT u.name, c_o.name AS origin, c_d.name AS destiny, f.origin_date, f.destiny_date, s.number, s.class, s.price 
				FROM services AS serv
				INNER JOIN seat AS s ON serv.service_id = s.id
				INNER JOIN flight AS f ON s.flight_id = f.id 
				INNER JOIN user AS u ON f.airline_id = u.id
				INNER JOIN city AS c_o ON f.origin_id = c_o.id
				INNER JOIN city AS c_d ON f.destiny_id = c_d.id
				WHERE serv.cart_id = :cart_id AND serv.type = 'flight'";
        $stmt = $con->prepare ( $sql );
        $stmt->bindParam (':cart_id', $id, PDO::PARAM_INT );
        $stmt->execute ();
        $rows = $stmt->fetchAll ();
        return $rows;
    }

    public function obtener_habitaciones($id){
        $con = $this->getConnection ();
        $sql = "SELECT u.name, c.name AS city, h.address, rr.date_in, rr.date_out, h.quality, r.number, r.beds, r.price 
				FROM services AS serv
				INNER JOIN room AS r ON serv.service_id = r.id
                INNER JOIN hotel AS h ON h.id = r.hotel_id
				INNER JOIN user AS u ON r.hotel_id = u.id
				INNER JOIN city AS c ON h.city_id = c.id
				INNER JOIN room_reserve AS rr ON rr.id_room = r.id
				WHERE serv.cart_id = :cart_id AND serv.type = 'room'";
        $stmt = $con->prepare ( $sql );
        $stmt->bindParam (':cart_id', $id, PDO::PARAM_INT );
        $stmt->execute ();
        $rows = $stmt->fetchAll ();
        return $rows;
    }

    public function obtener_vehiculos($id){
        $con = $this->getConnection ();
        $sql = "SELECT u.name, c.name AS city, vr.date_in, vr.date_out, v.fuel, v.slots, v.price 
				FROM services AS serv
				INNER JOIN vehicle AS v ON serv.service_id = v.id
				INNER JOIN user AS u ON v.concessionaire_id = u.id
				INNER JOIN city AS c ON v.city_id = c.id
				INNER JOIN vehicle_reserve AS vr ON vr.id_vehicle = v.id
				WHERE serv.cart_id = :cart_id AND serv.type = 'vehicle'";
        $stmt = $con->prepare ( $sql );
        $stmt->bindParam (':cart_id', $id, PDO::PARAM_INT );
        $stmt->execute ();
        $rows = $stmt->fetchAll ();
        return $rows;
    }
}