<?php

class HistorialController extends BaseController
{

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

    public function listar_historial()
    {

        $carritos = HistorialRepository::getInstance()->obtener_carritos($_SESSION['id']);

        $params['carritos'] = $carritos;

        $view = new HistorialView();
        $view->listar_historial($params);
    }

    public function detalle_carrito($data)
    {

        $vuelos = HistorialRepository::getInstance()->obtener_vuelos($data['id']);
        $habitaciones = HistorialRepository::getInstance()->obtener_habitaciones($data['id']);
        $vehiculos = HistorialRepository::getInstance()->obtener_vehiculos($data['id']);

        if (count($vuelos) != 0) {
            $params['vuelos'] = $vuelos;
        }
        if (count($habitaciones) != 0) {
            $params['habitaciones'] = $habitaciones;
        }
        if (count($vehiculos) != 0) {
            $params['vehiculos'] = $vehiculos;
        }

        $view = new HistorialView();
        $view->detalle_carrito($params);
    }

}