<?php

/**
 * Description of TwigView
 *
 * @author Diaz
 */
require_once './vendor/autoload.php';

abstract class TwigView
{

    private static $twig;

    public static function getTwig()
    {

        if (!isset(self::$twig)) {

            Twig_Autoloader::register();
            $loader = new Twig_Loader_Filesystem('./templates');
            self::$twig = new Twig_Environment($loader, array('auto_reload' => true));
            self::$twig->addGlobal('session', $_SESSION);

            /* Add a function to check if the user is logged in */
            $isLoggedFunction = new Twig_SimpleFunction('isLogged', function () {
                if (isset($_SESSION['logged']) && $_SESSION['logged'] == true) {
                    return 1;
                } else return 0;
            });

            /* Flash messages */
            $flashMessages = new Twig_SimpleFunction('flashMessages', function () {
                $msg = new \Plasticbrain\FlashMessages\FlashMessages();
                $msg->display();
            });

            /* Binary to boolean */
            $mapBinaryToBoolean = new Twig_SimpleFunction('mapBinaryToBoolean', function ($value) {
                return $value ? "Si" : "No";
            });

            /* Binary to boolean */
            $userStatusMap = new Twig_SimpleFunction('userStatusMap', function ($value) {
                return $value ? "Activo" : "Bloqueado";
            });

            /* Generate user pagination link */
            $generateUserPaginationLink = new Twig_SimpleFunction('generateUserPaginationLink', function ($searchTerms
                , $pageNumber) {

                $URL = "?action=user_list&page=" . $pageNumber;

                foreach ($searchTerms as $key => $value) {
                    $URL .= "&" . $key . "=" . $value;
                }

                return $URL;
            });

            /* Generate patient pagination link */
            $generatePatientPaginationLink = new Twig_SimpleFunction('generatePatientPaginationLink', function ($searchTerms
                , $pageNumber) {

                $URL = "?action=patient_list&page=" . $pageNumber;

                foreach ($searchTerms as $key => $value) {
                    $URL .= "&" . $key . "=" . $value;
                }

                return $URL;
            });

            /* Check for search terms */
            $searchTermInput = new Twig_SimpleFunction('searchTermInput', function ($searchTerms
                , $field) {

                if (isset($searchTerms[$field])) {
                    return $searchTerms[$field];
                } else {
                    return "";
                }

            });

            /* Check if a user has sufficient permissions for a given action*/
            $isAuthorized = new Twig_SimpleFunction('isAuthorized', function ($permission) {
                return UserController::getInstance()->isAuthorized($permission);
            });

//            /* Return the hospital's title */
//            $getHospitalInfo = new Twig_SimpleFunction('getHospitalInfo', function () {
//                return ConfigurationController::getInstance()->getHospitalInformation(false);
//            });

//            /* Checks if the rol is active for a user*/
//            $checkActiveUserRol = new Twig_SimpleFunction('checkActiveUserRol', function ($rol, $activeUserRoles) {
//
//                foreach ($activeUserRoles as $activeRol) {
//                    if ($activeRol->getName() == $rol->getName()) {
//                        return true;
//                    }
//                }
//
//                return false;
//            });

//            /* Checks if the option is selected*/
//            $isOptionSelected = new Twig_SimpleFunction('isOptionSelected', function ($currentId, $selectedId) {
//                if ($currentId == $selectedId) {
//                    return "selected";
//                }
//
//                return "";
//            });


            self::$twig->addFunction($isLoggedFunction);
            self::$twig->addFunction($flashMessages);
            self::$twig->addFunction($mapBinaryToBoolean);
            self::$twig->addFunction($userStatusMap);
            self::$twig->addFunction($generateUserPaginationLink);
            self::$twig->addFunction($generatePatientPaginationLink);
            self::$twig->addFunction($searchTermInput);
            self::$twig->addFunction($isAuthorized);
//            self::$twig->addFunction($getHospitalInfo);
//            self::$twig->addFunction($checkActiveUserRol);
//            self::$twig->addFunction($isOptionSelected);
//            self::$twig->addFunction($generateHealthControlPaginationLink);

        }
        return self::$twig;
    }

}
