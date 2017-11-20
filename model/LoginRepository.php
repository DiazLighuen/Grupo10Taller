<?php

/**
 * Class Login
 */
class LoginRepository extends PDORepository
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

    public function findUserWithPassword($userData)
    {
        /* Query the DB for a username matching a password */
        $rows = $this->queryList("SELECT id, email, username, status, modify, created, first_name, 
        last_name FROM user WHERE username=? AND password =?;", [$userData['username'], $userData['password']]);
        $user = null;
        $userData = array();

        /* If any row is returned, the login is valid */
        if (count($rows) != 0) {

            $userId = $rows[0]['id'];
            $permissions = $this->getPermissions($userId);

            $roles = $this->getRoles($userId);

            foreach ($rows as $element) {
                $user = new UserModel($element['id'], $element['email'], $element['username'], $element['status'],
                    $element['modify'], $element['created'], $element['first_name'], $element['last_name'], $roles,
                    $permissions);

                $userData['user'] = $user;
                $userData['status'] = $element['status'];
            }
        }

        return $userData;
    }

    /**
     * this function return a array with the roles of the user
     * @userId {Array}
     * @return {Array} of objects RolModel
     */
    private function getRoles($userId)
    {
        $rol = array();
        $roles = $this->queryList("SELECT * FROM rol AS r INNER JOIN user_rol AS ur ON ur.id_rol = r.id 
WHERE id_user =?;", [$userId]);

        if (count($roles) != 0) {
            foreach ($roles as &$item) {
                array_push($rol, new RolModel($item['id'], $item['name']));
            }
        }
        return $rol;
    }

    private function getPermissions($userId)
    {
        /* Query the DB for a permissions matching a id */
        $rows = $this->queryList("SELECT p.name 
                                      FROM  user_rol AS ur  INNER JOIN rol AS r ON ur.id_rol = 
                                      r.id INNER JOIN rol_permission AS rp ON r.id = rp.id_rol INNER JOIN permission AS 
                                      p ON rp.id_permission = p.id 
                                      WHERE ur.id_user=?;", [$userId]);

        $permissions = array();
        if (count($rows) != 0) {
            foreach ($rows as &$element) {
                $permissions[$element[0]] = true;

            }
        }

        return $permissions;
    }
}
