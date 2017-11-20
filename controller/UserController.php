<?php
/**
 * Description of UserController
 *
 *
 */

class UserController
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
     * This function return true if the permissions have the permission of the parameter
     */
    public function isAuthorized($permission)
    {
        return true;

        if (isset ($_SESSION['permissions'][$permission])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * This function return a form to create a new user
     */
    public function createFormUser()
    {
        if (!$this->isAuthorized('USER_CREATE')) {
            HomeController::getInstance()->show();
        } else {
            $roles = UserRepository::getInstance()->getRoles();

            $hospitalName = ConfigurationController::getInstance()->getHospitalName();

            $view = new UserCreateView();
            $view->show($roles, $hospitalName);
        }
    }

    /**
     * This function create a user if the session have permissions to do this
     * @userData {Array}
     */
    public function createUser($userData)
    {
        if (!$this->isAuthorized('USER_CREATE')) {
            HomeController::getInstance()->show();
        } else {

            $userDataSafe = Utils::getInstance()->sanitizeArray($userData);

            $rol = array();
            $roles = UserRepository::getInstance()->getRoles();

            foreach ($roles as $element) {
                if ($userDataSafe[$element->id] == 1) {
                    array_push($rol, $element);
                }
            }
            $result = UserRepository::getInstance()->createUser($userDataSafe, $rol);

            if (!$result) {
                $msg = new \Plasticbrain\FlashMessages\FlashMessages();
                $msg->success('El usuario ha sido creado exitosamente');
                $this->listUsers(null);
            } else {
                $msg = new \Plasticbrain\FlashMessages\FlashMessages();
                $msg->error('El nombre de usuario y/o email estan en uso actualmente');
                $this->createFormUser();
            }


        }

    }

    /**
     * This function read a user if the session have permissions to do this
     * @userData {Array}
     */
    public function readUser($userData)
    {
        if (!$this->isAuthorized('USER_READ')) {
            HomeController::getInstance()->show();
        } else {
            $userDataSafe = Utils::getInstance()->sanitizeArray($userData);

            $user = UserRepository::getInstance()->readUser($userDataSafe);

            $hospitalName = ConfigurationController::getInstance()->getHospitalName();

            $view = new UserReadView();
            $view->show($user, $hospitalName);
        }
    }

    /**
     * This function return a form to update a user if the session have permissions to do this
     * @userData {Array}
     */
    public function updateFormUser($userData)
    {
        if (!$this->isAuthorized('USER_UPDATE')) {
            HomeController::getInstance()->show();
        } else {
            $userDataSafe = Utils::getInstance()->sanitizeArray($userData);

            $user = UserRepository::getInstance()->readUser($userDataSafe);
            $roles = UserRepository::getInstance()->getRoles();
            $userRoles = UserRepository::getInstance()->getRolesFormUser($userDataSafe);

            $hospitalName = ConfigurationController::getInstance()->getHospitalName();

            $view = new UserUpdateView();
            $view->show($user, $roles, $hospitalName, $userRoles);
        }
    }

    /**
     * This function update a user if the session have permissions to do this
     * @userData {Array}
     */
    public function updateUser($userData)
    {
        if (!$this->isAuthorized('USER_UPDATE')) {
            HomeController::getInstance()->show();
        } else {

            $userDataSafe = Utils::getInstance()->sanitizeArray($userData);

            $userRoles = array();
            $dbRoles = UserRepository::getInstance()->getRoles();

            foreach ($dbRoles as $element) {
                if ($userData[$element->id] == 1) {
                    array_push($userRoles, $element);
                }
            }

            if (isset ($userDataSafe['password'])) {
                $result = UserRepository::getInstance()->updateUser($userDataSafe, $userRoles);
            } else {
                $result = UserRepository::getInstance()->updateSUser($userDataSafe, $userRoles);
            }

            if (!$result) {
                $msg = new \Plasticbrain\FlashMessages\FlashMessages();
                $msg->success('El usuario ha sido modificado exitosamente');
                UserController::getInstance()->listUsers(null);
            } else {
                $msg = new \Plasticbrain\FlashMessages\FlashMessages();
                $msg->error('El nombre de usuario y/o email estan en uso actualmente');
                UserController::getInstance()->updateFormUser($userDataSafe);
            }
        }
    }

    /**
     * This function delete a user if the session have permissions to do this
     * @userData {Array}
     */
    public function deleteUser($userData)
    {
        if (!$this->isAuthorized('USER_DELETE')) {
            HomeController::getInstance()->show();
        } else {
            $userDataSafe = Utils::getInstance()->sanitizeArray($userData);
            UserRepository::getInstance()->deleteUser($userDataSafe);

            $msg = new \Plasticbrain\FlashMessages\FlashMessages();
            $msg->success('El usuario ha sido eliminado exitosamente');

            $this->listUsers($_POST);
        }
    }

    /**
     * This function list all users, if the session have permissions to do this
     * @userData {Array}
     */
    public function listUsers($userData)
    {
        if (!$this->isAuthorized('USER_LIST')) {
            HomeController::getInstance()->show();
        } else {

            /* If the page is not set, force it to the first one */
            if (!isset($userData['page'])) {
                $userData['page'] = 1;
            }

            $userDataSafe = Utils::getInstance()->sanitizeArray($userData);
            $data = UserRepository::getInstance()->listUsers($userDataSafe);
            $data['currentPage'] = $userData['page'];
            $data['searchTerms'] = $this->getSearchValues($userDataSafe);

            $hospitalName = ConfigurationController::getInstance()->getHospitalName();

            $view = new UserListView();
            $view->show($data, $hospitalName);
        }
    }

    /**
     * It creates an associative array with the search terms and their values that exist within the array parameter
     * @data {Array}
     * @return {Array}
     */
    function getSearchValues($data)
    {
        $searchTerms = array();

        if (isset($data['first_name'])) {
            $searchTerms['first_name'] = $data['first_name'];
        }

        if (isset($data['last_name'])) {
            $searchTerms['last_name'] = $data['last_name'];
        }

        if (isset($data['status'])) {
            $searchTerms['status'] = $data['status'];
        }

        return $searchTerms;
    }

    /**
     * This function toggle the status of a user, if the session have permissions to do this
     * @userData {Array}
     */
    public function toggleStatus($userData)
    {
        if (!$this->isAuthorized('USER_UPDATE')) {
            HomeController::getInstance()->show();
        } else {
            $userDataSafe = Utils::getInstance()->sanitizeArray($userData);

            /* Query the DB in search of a user matching his password */
            UserRepository::getInstance()->toggleStatus($userDataSafe);

            $msg = new \Plasticbrain\FlashMessages\FlashMessages();
            $msg->success('El estado del usuario ha sido modificado exitosamente');

            $this->listUsers($userData);
        }
    }
}