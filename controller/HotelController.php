<?php

/**
 * Description of HotelController
 *
 */
class HotelController extends BaseController{

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

    /**
     * Renders the Hotel view
     */
    public function buscar_hotel(){
      // para que quede seleccionada la ciudad que eligio el usuario
      $params['ciudad_seleccionada'] = '';
      // Presiona Buscar, ejecutamos la busqueda en base a los filtros del usuario
      $ciudades = BaseRepository::getInstance()->buscar_ciudades();
      $params['ciudades'] = $ciudades;
      $hospitalName = 'TresVagos';
      $params['hospitalName'] = $hospitalName;
      $view = new HotelView();
      $view->buscar_hotel($params);
    }

    public function listar_hoteles($data){
        // Presiona Buscar, ejecutamos la busqueda en base a los filtros del usuario
        if ($this->is_method_post()){
            $fecha_desde = $data['fecha_desde'];
            $fecha_hasta = $data['fecha_hasta'];
            $ciudad = $data['ciudad'];
            $hoteles = HotelRepository::getInstance()->buscar_hotel($fecha_desde,$fecha_hasta,$ciudad);
            $params['hoteles'] = $hoteles;
            // hay que cargar los datos de la busqueda
            $params['fecha_desde'] = $data['fecha_desde'];
            $params['fecha_hasta'] = $data['fecha_hasta'];
            $params['ciudad_seleccionada'] = $data['ciudad'];
        }
        $hospitalName = 'TresVagos';
        $params['hospitalName'] = $hospitalName;
        $view = new HotelListarView();
        $view->listar_hotel($params);
    }

}
