<?php

/**
 * Description of InicioController
 *
 */
class InicioController
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

    /**
     * Renders the Inicio view
     */
    public function inicio()
    {
        $view = new InicioView();

        $hospitalName = 'TresVagos';

        $view->inicio($hospitalName);
    }

}
