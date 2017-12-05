<?php

/**
 * Description of VueloController
 *
 */
class VueloController extends BaseController{

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

    public function buscar_vuelo(){
      // para que quede seleccionada la ciudad que eligio el usuario
      $params['ciudad_seleccionada'] = '';
      $ciudades = BaseRepository::getInstance()->buscar_ciudades();
      $params['ciudades'] = $ciudades;
      $hospitalName = 'TresVagos';
      $params['hospitalName'] = $hospitalName;
      $view = new VueloView();
      $view->buscar_vuelo($params);
    }

    public function listar_vuelos($data){
        // Presiona Buscar, ejecutamos la busqueda en base a los filtros del usuario
        if ($this->is_method_post()){
			$fecha_desde = $_POST['fecha_desde'];
			$fecha_hasta = $_POST['fecha_hasta'];
			$ciudad_origen = $_POST['ciudad_origen'];
			$ciudad_destino = $_POST['ciudad_destino'];
			$vuelos = VueloRepository::getInstance()->buscar_vuelo($fecha_desde,$fecha_hasta,$ciudad_origen,$ciudad_destino);

			$params['vuelos'] = $vuelos;
			// hay que cargar los datos de la busqueda
			$params['fecha_desde'] = $_POST['fecha_desde'];
			$params['fecha_hasta'] = $_POST['fecha_hasta'];
			$params['ciudad_origen_seleccionada'] = $_POST['ciudad_origen'];
			$params['ciudad_destino_seleccionada'] = $_POST['ciudad_destino'];
        }
        $hospitalName = 'TresVagos';
        $params['hospitalName'] = $hospitalName;
        $view = new VueloListarView();
        $view->listar_vuelos($params);
    }	

	public function vuelo_show($data){
		
		$vuelo = VueloRepository::getInstance()->vuelo_show($data['id_seat']);
		$hospitalName = 'TresVagos';
		$params['hospitalName'] = $hospitalName;
		$params['vuelo'] = $vuelo;
		$view = new VueloShowView();
		$view->vuelo_show($params);
	}
}
