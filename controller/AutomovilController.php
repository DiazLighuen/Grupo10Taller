<?php

/**
 * Description of AutomovilController
 *
 */
class AutomovilController extends BaseController{

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

    public function listar_automoviles($data){
        // Presiona Buscar, ejecutamos la busqueda en base a los filtros del usuario
        if ($this->is_method_post()){
			$fecha_desde = $_POST['fecha_desde'];
			$fecha_hasta = $_POST['fecha_hasta'];
			$_SESSION['fecha_desde'] = $fecha_desde;
			$_SESSION['fecha_hasta'] = $fecha_hasta;
			$ciudad = $_POST['ciudad'];
			$precio = $_POST['precio'];
			$automoviles = AutomovilRepository::getInstance()->buscar_automovil($fecha_desde,$fecha_hasta,$ciudad,$precio);
			$params['automoviles'] = $automoviles;
			// hay que cargar los datos de la busqueda
			$params['fecha_desde'] = $_POST['fecha_desde'];
			$params['fecha_hasta'] = $_POST['fecha_hasta'];
			$params['ciudad_seleccionada'] = $_POST['ciudad'];
			$params['precio_seleccionado'] = $_POST['precio'];
        }
        $hospitalName = 'TresVagos';
        $params['hospitalName'] = $hospitalName;
        $view = new AutomovilListarView();
        $view->listar_automovil($params);
    }	
	
	public function buscar_automovil(){
		// para que quede seleccionada la ciudad que eligio el usuario
		$params['ciudad_seleccionada'] = '';
		$precios = AutomovilRepository::getInstance()->listar_precios();
		$ciudades = BaseRepository::getInstance()->buscar_ciudades();
		$params['ciudades'] = $ciudades;
		$params['precios'] = $precios;
		$hospitalName = 'TresVagos';
		$params['hospitalName'] = $hospitalName;
		$view = new AutomovilView();
		$view->buscar_automovil($params);
    }

	public function automovil_show($data){
		
		$automovil = AutomovilRepository::getInstance()->automovil_show($data['id_vehicle']);
		$hospitalName = 'TresVagos';
		$params['hospitalName'] = $hospitalName;
		$params['automovil'] = $automovil;
		$view = new AutomovilShowView();
		$view->automovil_show($params);
	}
}
