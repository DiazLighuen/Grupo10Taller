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
      if ($this->is_method_post()){
        $fecha_desde = $_POST['fecha_desde'];
        $fecha_hasta = $_POST['fecha_hasta'];
        $ciudad = $_POST['ciudad'];
        $hoteles = HotelRepository::getInstance()->buscar_hotel($fecha_desde,$fecha_hasta,$ciudad);
        $params['hoteles'] = $hoteles;
        // hay que cargar los datos de la busqueda
        $params['fecha_desde'] = $_POST['fecha_desde'];
        $params['fecha_hasta'] = $_POST['fecha_hasta'];
        $params['ciudad_seleccionada'] = $_POST['ciudad'];
      }
      $ciudades = BaseRepository::getInstance()->buscar_ciudades();
      $params['ciudades'] = $ciudades;
      $hospitalName = 'TresVagos';
      $params['hospitalName'] = $hospitalName;
      $view = new HotelView();
      $view->buscar_hotel($params);
    }

}
