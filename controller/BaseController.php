<?php

/**
 * Description of BaseController
 *
 */
class BaseController
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

    public function is_method_post(){
      return $_SERVER['REQUEST_METHOD'] == 'POST';
    }
	
	public function redirect($action){
		if($action !=''){
			$action = '?action='.$action;
		}
		header('Location:index.php'.$action);
	}

}
