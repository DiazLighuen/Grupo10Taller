<?php

class CarritoController extends BaseController{

    private static $instance;

    public static function getInstance()
    {

        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct()
    {

    }

    public function agregar_a_carrito($data){
			
		// creo el carrito a mano ahora para probar, ya que no tengo en la sesion el id del usuario logueado
		$carrito_id = CarritoRepository::getInstance()->crear_carrito();
		
		echo var_dump($carrito_id);
		
        // agregamos al carrito el servicio
        if ($this->is_method_post()){
            $service_id = $data['id'];
            $type = $data['type'];
            $service_id_nuevo = CarritoRepository::getInstance()->agregar_a_carrito($service_id,$type,$carrito_id);
			echo var_dump($service_id_nuevo);
        }
		
		die;
    }

}
