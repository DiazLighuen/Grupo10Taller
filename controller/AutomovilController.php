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

    /**
     * Renders the Automovil view
     */
    public function buscar_automovil(){
      // para que quede seleccionada la ciudad que eligio el usuario
      //$params['ciudad_seleccionada'] = '';
      // Presiona Buscar, ejecutamos la busqueda en base a los filtros del usuario
      if ($this->is_method_post()){
        $fecha_desde = $_POST['fecha_desde'];
        $fecha_hasta = $_POST['fecha_hasta'];
        $ciudad = $_POST['ciudad'];
        $tipo_automovil = $_POST['tipo_automovil'];
        $precio = $_POST['precio'];
        $automoviles = AutomovilRepository::getInstance()->buscar_automovil($fecha_desde,$fecha_hasta,$ciudad,$tipo_automovil,$precio);
        $params['automoviles'] = $automoviles;
        // hay que cargar los datos de la busqueda
        $params['fecha_desde'] = $_POST['fecha_desde'];
        $params['fecha_hasta'] = $_POST['fecha_hasta'];
        $params['ciudad_seleccionada'] = $_POST['ciudad'];
        $params['tipo_automovil_seleccionado'] = $_POST['tipo_automovil'];
        $params['precio_seleccionado'] = $_POST['precio'];
      }
      $tipos_automoviles = AutomovilRepository::getInstance()->listar_tipos_automoviles();
      $precios = AutomovilRepository::getInstance()->listar_precios();
      $ciudades = BaseRepository::getInstance()->buscar_ciudades();
      $params['ciudades'] = $ciudades;
      $params['tipos_automoviles'] = $tipos_automoviles;
      $params['precios'] = $precios;
      $hospitalName = 'TresVagos';
      $params['hospitalName'] = $hospitalName;
      $view = new AutomovilView();
      $view->buscar_automovil($params);
    }

}
