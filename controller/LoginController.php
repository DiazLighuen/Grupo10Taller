<?php

/**
 * Description of LoginController
 *
 */
class LoginController
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
     * Checks if the username and password match and logs the user in
     * @loginData {Object}
     */
    public function loginCheck($loginData)
    {
        /* Check for required fields */
        if (!isset($loginData['username']) || !isset($loginData['password'])) {
            $this->show();
            return;
        }


        /* Sanitize loginData and check for empty fields */
        $loginDataSafe = Utils::getInstance()->sanitizeArray($loginData);

        if (!Utils::getInstance()->checkEmptyFields($loginDataSafe)) {
            $this->show();
            return;
        }

        /* Query the DB in search of a user matching his password */
        $userData = LoginRepository::getInstance()->findUserWithPassword($loginDataSafe);

        /* Check if the user exists and is not blocked*/
        $validUser = (isset($userData['user'])) ? true : false;
        $allowedUser = (isset($userData['status']) && $userData['status'] == 1) ? true : false;


        if ($validUser && $allowedUser) {
            $_SESSION['id'] = $userData['user']->getId();
            $_SESSION['username'] = $userData['user']->getUsername();
            $_SESSION['status'] = $userData['user']->getStatus();
            $_SESSION['name'] = $userData['user']->getName();
            $_SESSION['permisions'] = $userData['user']->getPermisions();
            $_SESSION['logged'] = true;


            http_response_code(200);
            $response = ['message' => 'Bienvenido a TresVagos'];
            echo json_encode($response);

        } else {
            if (!$validUser) {
                $response = ['message' => 'El usuario o contraseña ingresados son invalidos'];


                http_response_code(400);

                echo json_encode($response);
            }
        }
    }

    /**
     * Checks if the user is logged in
     * @logged int
     */
    public function isLogged()
    {
        if (isset($_SESSION['logged']) && $_SESSION['logged'] == true) {
            return 1;
        } else return 0;
    }

    /**
     * Logs the user out and redirects to home
     */
    public function logout()
    {
        session_unset();
        session_destroy();
        InicioController::getInstance()->inicio();
    }

    /**
     * Renders the Login view
     */
    public function show()
    {
        $view = new LoginView();

        $hospitalName = 'TresVagos';

        $view->show($hospitalName);
    }

}
