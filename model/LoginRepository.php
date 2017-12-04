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

    public function __construct()
    {

    }

    public function findUserWithPassword($userData)
    {
        /* Query the DB for a username matching a password */
        $rows = $this->queryList("SELECT id, username, status, name, permisions FROM user WHERE username=? AND password =?;", [$userData['username'], $userData['password']]);
        $user = null;
        $userData = array();

        /* If any row is returned, the login is valid */
        if (count($rows) != 0) {

            foreach ($rows as $element) {
                $user = new UserModel($element['id'], $element['username'], $element['status'], $element['name'], $element['permisions']);


                $userData['user'] = $user;
                $userData['status'] = $element['status'];
            }
        }

        return $userData;
    }

}
