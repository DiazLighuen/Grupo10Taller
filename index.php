<?php
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);

/*Utils Functions*/
require_once('utils/utils.php');
require_once('utils/FlashMessages.php');

/*Model Files*/
require_once('model/PDORepository.php');
require_once('model/BaseRepository.php');
require_once('model/LoginRepository.php');
require_once('model/HotelRepository.php');
require_once('model/VueloRepository.php');
require_once('model/AutomovilRepository.php');
require_once('model/UserModel.php');
require_once('model/CarritoRepository.php');

/*Controller Files*/
require_once('controller/BaseController.php');
require_once('controller/LoginController.php');
require_once('controller/InicioController.php');
require_once('controller/UserController.php');
require_once('controller/HotelController.php');
require_once('controller/VueloController.php');
require_once('controller/AutomovilController.php');
require_once('controller/CarritoController.php');

/*View Files*/
require_once('view/TwigView.php');
require_once('view/LoginView.php');
require_once('view/Home.php');
require_once('view/InicioView.php');
require_once('view/HotelView.php');
require_once('view/HotelListarView.php');
require_once('view/VueloView.php');
require_once('view/VueloListarView.php');
require_once('view/AutomovilView.php');
require_once('view/AutomovilListarView.php');
require_once('view/CarritoView.php');

session_start();

if (!isset($_GET["action"])) {
    InicioController::getInstance()->inicio();
} else {
    if (isset($_GET["action"])) {
        switch ($_GET['action']) {
            case 'login' :
                LoginController::getInstance()->show();
                break;
            case 'login_check' :
                LoginController::getInstance()->loginCheck($_POST);
                break;
            case 'logout' :
                LoginController::getInstance()->logout();
                break;
            case 'home' :
                InicioController::getInstance()->inicio();
                break;
            case 'buscar_hotel' :
                HotelController::getInstance()->buscar_hotel();
                break;
            case 'listar_hoteles' :
                HotelController::getInstance()->listar_hoteles($_POST);
                break;
            case 'buscar_vuelo' :
                VueloController::getInstance()->buscar_vuelo();
                break;
            case 'listar_vuelos' :
                VueloController::getInstance()->listar_vuelos($_POST);
                break;
            case 'buscar_automovil' :
                AutomovilController::getInstance()->buscar_automovil();
                break;
            case 'listar_automoviles' :
                AutomovilController::getInstance()->listar_automoviles($_POST);
                break;
            case 'agregar_a_carrito' :
                CarritoController::getInstance()->agregar_a_carrito($_POST);
                break;
            case 'carrito' :
                CarritoController::getInstance()->listar_carrito();
                break;
            case 'eliminar_servicio_carrito' :
                CarritoController::getInstance()->eliminar_servicio_carrito();
                break;
            case 'pagar_carrito' :
                CarritoController::getInstance()->pagar_carrito();
                break;
            default:
                InicioController::getInstance()->inicio();
                break;
        }
    }
}
