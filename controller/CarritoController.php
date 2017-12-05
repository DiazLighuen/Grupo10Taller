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
		$usuario_id = $_SESSION['id'];
		$fecha_desde = $_SESSION['fecha_desde'];
		$fecha_hasta = $_SESSION['fecha_hasta'];
		// busco el carrito del usuario logueado
		$carrito_id = CarritoRepository::getInstance()->buscar_carrito($usuario_id);	
		$service_id = $data['id_servicio'];
		$type = $data['type'];
		$service_id_nuevo = CarritoRepository::getInstance()->agregar_a_carrito_y_reservar($service_id,$type,$carrito_id,$usuario_id,$fecha_desde,$fecha_hasta);
		if ($service_id_nuevo){
			$this->redirect('carrito');
		}
		else{
			// arrojar mensaje de error
		}
    }
	
	public function listar_carrito(){
      //if ($this->is_method_post()){
      //  $this->pagar_carrito();
      //}
	  //else {
		$servicios = CarritoRepository::getInstance()->obtener_servicios_carrito();
		//var_dump($servicios);
		$carrito = array();
		$_SESSION['items'] = 0;
		$_SESSION['imp_total'] = 0;
        foreach ($servicios as $servicio) {
			$servicio_detalle = CarritoRepository::getInstance()->obtener_servicio_detalle($servicio[0], $servicio[1], $servicio[2]);
			$carrito[] = $servicio_detalle;
			$cart_id = $servicio_detalle['cart_id'];
			//array_push($carrito, $servicio_detalle);
			$_SESSION['items'] += 1;
			$_SESSION['imp_total'] += $servicio_detalle['total'];
        }
		$params['carrito'] = $carrito;
		$hospitalName = 'TresVagos';
		$params['hospitalName'] = $hospitalName;
		$params['items'] = $_SESSION['items'];
		$params['cart_id'] = $cart_id;
		$params['imp_total'] = $_SESSION['imp_total'];
		$view = new CarritoView();
		$view->listar_carrito($params);
    }
	
    public function eliminar_servicio_carrito(){
		$cart_id = $_GET['cart_id'];
		$type = $_GET['type'];
		$service_id = $_GET['id'];
		$borrar = CarritoRepository::getInstance()->eliminar_servicio_carrito($cart_id, $type, $service_id);
		$this->listar_carrito();
		}	

	public function pagar_carrito(){
      if ($this->is_method_post()){
		//var_dump($_SESSION['imp_total']);
        //pagar_carrito();
		
		$servicios = CarritoRepository::getInstance()->pagar_carrito($_SESSION['cart_id']);
		
		InicioController::getInstance()->inicio();
      }
	  else {
		$servicios = CarritoRepository::getInstance()->obtener_servicios_carrito();
		//var_dump($servicios);
		$carrito = array();
		$_SESSION['items'] = 0;
		$_SESSION['imp_total'] = 0;
        foreach ($servicios as $servicio) {
			//var_dump($servicio);
			//var_dump($servicio[0]);
			//$servicio_detalle = CarritoRepository::getInstance()->obtener_servicio_detalle($servicio->cart_id, $servicio->type, $servicio->service_id);
			$servicio_detalle = CarritoRepository::getInstance()->obtener_servicio_detalle($servicio[0], $servicio[1], $servicio[2]);
			//var_dump($servicio_detalle);
			array_push($carrito, $servicio_detalle);
			$_SESSION['items'] += 1;
			$_SESSION['imp_total'] += $servicio_detalle[0][4];
			$_SESSION['cart_id'] = $servicio_detalle[0][0];
        }
			//var_dump($_SESSION['items']);
			//var_dump($_SESSION['imp_total']);
		//var_dump($carrito);
		$params['carrito'] = $carrito;
		//$hospitalName = 'TresVagos';
		//$params['hospitalName'] = $hospitalName;
		$params['items'] = $_SESSION['items'];
		$params['imp_total'] = $_SESSION['imp_total'];
		$view = new CarritoView();
		//var_dump($carrito);
		$view->pagar_carrito($params);
		}	
    }

}
