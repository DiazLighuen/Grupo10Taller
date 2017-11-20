<?php
ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);

require_once('model/PDORepository.php');
require_once('controller/LoginController.php');
require_once('view/TwigView.php');
require_once('view/Home.php');

if(!isset($_GET["action"])){
    LoginController::getInstance()->show();
}

