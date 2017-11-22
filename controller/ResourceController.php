<?php

class ResourceController extends BaseController{

    private static $instance;

    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
    }

    public function home(){
        $view = new HomeView();
        $view->show();
    }

}
