<?php
ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);

/*Utils Functions*/
require_once('utils/utils.php');
require_once('utils/FlashMessages.php');

/*Model Files*/
require_once('model/PDORepository.php');

/*Controller Files*/
require_once('controller/LoginController.php');
require_once('controller/UserController.php');

/*View Files*/
require_once('view/TwigView.php');
require_once('view/LoginView.php');
require_once('view/Home.php');

session_start();

if(!isset($_GET["action"])){
    LoginController::getInstance()->show();
}

