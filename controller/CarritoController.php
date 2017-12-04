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
		//var_dump($servicios);
		$carrito = array();
        foreach ($servicios as $servicio) {
			//var_dump($servicio);
			//var_dump($servicio[0]);
			//$servicio_detalle = CarritoRepository::getInstance()->obtener_servicio_detalle($servicio->cart_id, $servicio->type, $servicio->service_id);
			$servicio_detalle = CarritoRepository::getInstance()->obtener_servicio_detalle($servicio[0], $servicio[1], $servicio[2]);
			//var_dump($servicio_detalle);
			array_push($carrito, $servicio_detalle);
        }
		$params['carrito'] = $carrito;
		//$hospitalName = 'TresVagos';
		//$params['hospitalName'] = $hospitalName;
		$view = new CarritoView();
		//var_dump($carrito);
		$view->listar_carrito($params);
		}	
    }
	
    public function eliminar_servicio_carrito(){
		$tipo = $_GET['tipo'];
		$id = $_GET['id'];
		//$borrar = CarritoRepository::getInstance()->eliminar_servicio_carrito($tipo, $id);
		$this->listar_carrito();
		}	

}
