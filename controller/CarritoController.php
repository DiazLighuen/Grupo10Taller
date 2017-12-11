<?php
class CarritoController extends BaseController{
	
    private static $instance;
	
    public static function getInstance(){
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }
	
    private function __construct(){
    }
	
    public function agregar_a_carrito($data){
		$usuario_id = $_SESSION['id'];
		$fecha_desde = $_SESSION['fecha_desde'];
		$fecha_hasta = $_SESSION['fecha_hasta'];
		// busco el carrito del usuario logueado
		$carrito_id = CarritoRepository::getInstance()->buscar_carrito($usuario_id);	
		$service_id = $data['id_servicio'];
		$type = $data['type'];
		$price = $data['price'];
		$service_id_nuevo = CarritoRepository::getInstance()->agregar_a_carrito_y_reservar($service_id,$type,$carrito_id,$usuario_id,$fecha_desde,$fecha_hasta,$price);
		if ($service_id_nuevo){
			$this->redirect('carrito');
		}
    }
	
	public function listar_carrito(){
		$servicios = CarritoRepository::getInstance()->obtener_servicios_carrito();
		$cantidad_items = 0;
		$importe_total = 0;
        foreach ($servicios as $servicio) {
			$servicio_detalle = CarritoRepository::getInstance()->obtener_servicio_detalle($servicio[0], $servicio[1], $servicio[2]);
			$carrito[] = $servicio_detalle;
			// cart_id es el mismo para todos pero lo guardo en una variable para poder accederlo mas facil
			$cart_id = $servicio_detalle['cart_id'];
			$cantidad_items += 1;		
			$importe_total += $servicio_detalle['total'];
        }
		if (count($servicios) != 0) {
			$params['carrito'] = $carrito;
			$params['cart_id'] = $cart_id;
        }
		$hospitalName = 'TresVagos';
		$params['hospitalName'] = $hospitalName;
		$params['items'] = $cantidad_items;
		$params['imp_total'] = $importe_total;
		$view = new CarritoView();
		$view->listar_carrito($params);
    }
	
    public function eliminar_servicio_carrito($data){			
		$cart_id = $data['cart_id'];
		$type = $data['type'];
		$service_id = $data['id_servicio'];
		$price = $data['price'];
		$fecha_desde = $_SESSION['fecha_desde'];
		$fecha_hasta = $_SESSION['fecha_hasta'];
		// para el caso de room_reserve o vehicle_reserve
		$serv_id = $data['id_serv'];
		$borrar = CarritoRepository::getInstance()->eliminar_servicio_carrito($cart_id, $type, $service_id,$serv_id,$price,$fecha_desde,$fecha_hasta);
		$this->redirect('carrito');
	}	
	public function pagar_carrito($data){
        $params['cart_id']=$data['cart_id'];
        $params['items']=$data['items'];
        $params['imp_total']=$data['imp_total'];
		$view = new CarritoView();
		$view->pagar_carrito($params);
    }
    public function pago($data){
        $params['cart_id']=$data['cart_id'];
        $params['user_id']=$_SESSION['id'];
        CarritoRepository::getInstance()->pago($params);
        InicioController::getInstance()->home();
    }
}