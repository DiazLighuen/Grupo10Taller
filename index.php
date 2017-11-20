<?php
ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);

require_once('model/PDORepository.php');
require_once('controller/ResourceController.php');
require_once('view/TwigView.php');
require_once('view/HomeView.php');

if(!isset($_GET["action"])){
    ResourceController::getInstance()->home();
}

