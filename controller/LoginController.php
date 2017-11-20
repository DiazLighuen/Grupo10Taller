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

        if ((ConfigurationModel::getInstance()->getWebsiteStatus() == 'offline') & !UserRepository::getInstance()->isAdmin($userData)) {
            $validUser = false;
        }

        if ($validUser && $allowedUser) {
            $_SESSION['id'] = $userData['user']->getId();
            $_SESSION['email'] = $userData['user']->getEmail();
            $_SESSION['username'] = $userData['user']->getUsername();
            $_SESSION['status'] = $userData['user']->getStatus();
            $_SESSION['modify'] = $userData['user']->getModify();
            $_SESSION['create'] = $userData['user']->getCreate();
            $_SESSION['first_name'] = $userData['user']->getFirstName();
            $_SESSION['last_name'] = $userData['user']->getLastName();
            $_SESSION['rol'] = $userData['user']->getRol();
            $_SESSION['permissions'] = $userData['user']->getPermissions();
            $_SESSION['logged'] = true;

            http_response_code(200);
            $response = ['message' => 'Bienvenido a Hospital Gutierrez'];
            echo json_encode($response);

        } else {
            if ((ConfigurationModel::getInstance()->getWebsiteStatus() == 'offline')) {
                $response = ['message' => 'El sitio no esta disponible en este momento, intente mas tarde'];
            } elseif (!$validUser) {
                $response = ['message' => 'El usuario o contraseña ingresados son invalidos'];
            } elseif (!$allowedUser) {
                $response = ['message' => 'El usuario se encuentra actualmente bloqueado'];
            }

            http_response_code(400);

            echo json_encode($response);
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
        session_destroy();
        $_SESSION = array();
        HomeController::getInstance()->show();
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
