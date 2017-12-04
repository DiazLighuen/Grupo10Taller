<?php

/**
 * Description of AutomovilController
 *
 */
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

    public function listar_carrito(){
      if ($this->is_method_post()){
        //pagar_carrito()
      }
	  else {
		$servicios = CarritoRepository::getInstance()->obtener_servicios_carrito();
		$carrito = array();
        foreach ($servicios as $servicio) {
				$servicio_detalle = CarritoRepository::getInstance()->obtener_servicio_detalle($servicio->cart_id,$servicio->type,$servicio->service_id);
				array_push($carrito, $servicio_detalle);
        }
		$params['carrito'] = $carrito;
		$hospitalName = 'TresVagos';
		$params['hospitalName'] = $hospitalName;
		$view = new CarritoView();
		$view->listar_carrito($params);
		}	
    }
}
