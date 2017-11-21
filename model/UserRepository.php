<?php

/**
 *
 */
class UserRepository extends PDORepository
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
     * Create a new user in the table user. And create a connection in the table user_rol
     * @arguments {Array}
     */
    public function createUser($userData, $roles)
    {
        $userCheck = $this->queryList("SELECT id FROM user WHERE username= ? OR email= ? ", [$userData['username'], $userData['email']]);
        if (!isset($userCheck[0]['id'])) {
            $id_user = $this->queryInsertAndGetId("INSERT INTO user (email, password, username, status,
 modify, created,first_name, last_name) VALUES (?, ?, ?, ?, ?, ?, ?, ?)", [$userData['email'], $userData['password'], $userData['username'],
                $userData['status'], date('Y-m-d'), date('Y-m-d'), $userData['first_name'], $userData['last_name']]);

            foreach ($roles as $element) {
                $this->queryList("INSERT INTO user_rol (id_user, id_rol) VALUES (?, ?);", [$id_user, $element->id]);
            }
            return false;
        } else {
            return true;
        }
    }

    /**
     * Show all element of user with id=$id, except password
     * @arguments {Array}
     */
    public function getRolesFormUser($userData)
    {
        $roles = array();
        $rol = $this->queryList("SELECT r.id, r.name FROM rol AS r INNER JOIN user_rol AS ur ON ur.id_rol = r.id WHERE id_user =?;", [$userData['id']]);
        foreach ($rol as &$element) {
            array_push($roles, new RolModel($element['id'], $element['name']));
        }
        return $roles;

    }

    public function readUser($userData)
    {
        $user = array();
        $roles = array();

        $rol = $this->queryList("SELECT name FROM rol AS r INNER JOIN user_rol AS ur ON ur.id_rol = r.id WHERE id_user =?;", [$userData['id']]);

        foreach ($rol as &$element) {
            array_push($roles, new RolModel('', $element[0]));
        }

        $rows = $this->queryList("SELECT id, email, username, status, modify, created, first_name, 
        last_name FROM user WHERE id =?", [$userData['id']]);


        if (count($rows) != 0) {
            foreach ($rows as &$element) {
                $user = new UserModel($element['id'], $element['email'], $element['username'], $element['status'],
                    $element['modify'], $element['created'], $element['first_name'], $element['last_name'], $roles, '');
            }
        }
        return $user;
    }

    /**
     * Update a user, this overwrite all the elements of user with id = $id, and overwrite id_rol in the table user_rol
     * @arguments {Array}
     */
    public function updateUser($userData, $roles)
    {
        $userCheck = $this->queryList("SELECT id FROM user WHERE (username= ? OR email= ?) AND id!= ? ", [$userData['username'], $userData['email'], $userData['id'],]);
        if (!isset($userCheck[0]['id'])) {
            $this->queryList("UPDATE user SET email=?, password=?, username=?, status=?, modify=?, first_name=?, 
    last_name=? WHERE id = ?;", [$userData['email'], $userData['password'], $userData['username'], $userData['status'],
                date('Y-m-d'), $userData['first_name'], $userData['last_name'], $userData['id']]);

            $id_user = $userData['id'];

            $this->queryList("DELETE FROM user_rol WHERE id_user = ?;", [$id_user]);
            foreach ($roles as &$element) {
                $this->queryList("INSERT INTO user_rol (id_user, id_rol) VALUES (?, ?);", [$id_user, $element]);
            }
            return false;
        } else {
            return true;
        }
    }

    /**
     * Update a user, this overwrite all the elements of user with id = $id, and overwrite id_rol in the table user_rol
     * @arguments {Array}
     */
    public function updateSUser($userData, $roles)
    {
        $userCheck = $this->queryList("SELECT id FROM user WHERE (username= ? OR email= ?) AND id!= ? ", [$userData['username'], $userData['email'], $userData['id'],]);
        if (!isset($userCheck[0]['id'])) {
            $this->queryList("UPDATE user SET email=?, username=?, status=?, modify=?, first_name=?, 
last_name=? WHERE id = ?;", [$userData['email'], $userData['username'], $userData['status'],
                date('Y-m-d'), $userData['first_name'], $userData['last_name'], $userData['id']]);

            $id_user = $userData['id'];

            $this->queryList("DELETE FROM user_rol WHERE id_user = ?;", [$id_user]);
            foreach ($roles as $element) {
                $this->queryList("INSERT INTO user_rol (id_user, id_rol) VALUES (?, ?);", [$id_user, $element->getId()]);
            }
            return false;
        } else {
            return true;
        }

    }

    /**
     * Delete a user with the parameter $id
     * @arguments {Array}
     */
    public function deleteUser($userData)
    {
        $this->queryList("DELETE FROM user WHERE id = ?;", [$userData['id_user']]);
    }

    /**
     * return all of users in a object userModel
     * @arguments {Array}
     * @return {Array}
     */
    public function listUsers($userData)
    {
        $paginatedData = $this->pagination($userData);

        $answer = array();
        $totalUserCount = $this->filter($userData);

        $users = array();
        if (count($paginatedData['users']) != 0) {

            $rol = array();

            foreach ($paginatedData['users'] as &$element) {

                $roles = $this->queryList("SELECT name FROM rol AS r INNER JOIN user_rol AS ur ON ur.id_rol = r.id 
WHERE id_user =?;", [$element['id']]);

                foreach ($roles as &$item) {
                    array_push($rol, new RolModel('', $item[0]));
                }

                $user = new UserModel($element['id'], '', $element['username'], $element['status'], '', '',
                    $element['first_name'], $element['last_name'], $rol, '');

                array_push($users, $user);
            }
        }
        $answer['users'] = $users;
        $answer['totalUserCount'] = $totalUserCount;
        $answer['itemsPerPage'] = $paginatedData['itemsPerPage'];

        return $answer;
    }

    /**
     * return all of roles
     * @return {array}
     */
    public function getRoles()
    {
        $roles = array();

        $rows = $this->queryList("SELECT * FROM rol", []);

        foreach ($rows as $element) {
            array_push($roles, new RolModel($element['id'], $element['name']));
        }

        return $roles;
    }

    /**
     * toggle the status of the user in the parameter
     * @arguments {Array}
     */
    public function toggleStatus($userData)
    {
        $row = $this->queryList("SELECT status FROM user WHERE id = ?;", [$userData['id']]);

        if ($row[0][0] == 0) {
            $status = 1;
        } else {
            $status = 0;
        }

        $this->queryList("UPDATE user SET status=? WHERE id = ?;", [$status, $userData['id']]);
    }

    private function pagination($userData)
    {
        $paginationRow = $this->queryList("SELECT pagination_number FROM hospital", []);
        $pageNumber = (int)$userData['page']; // This casting is for PDO, it casts the parameter to int
        $paginationNumber = (int)$paginationRow[0]['pagination_number']; // Explained on
        $start = ($pageNumber - 1) * $paginationNumber;

        $data = array();

        /*PDO don't have a automatic optional parameter, so...
        The variable $p* are used to follow the relative order of the parameters, this parameters depends of the
        optional parameters in the query*/
        $p1 = 1;
        $p2 = 1;
        $p3 = 1;
        $p4 = 1;

        if (isset($userData['status'])) {
            if (isset($userData['first_name'])) {
                if (isset($userData['last_name'])) {
                    $sql = 'SELECT id, username, first_name, status, last_name FROM user WHERE 
first_name LIKE ? AND last_name LIKE ? AND status=? LIMIT ?,?';
                    $p1 = 1;
                    $p2 = 2;
                    $p3 = 3;
                    $p4 = 4;
                } else {
                    $sql = 'SELECT id, username, first_name, status, last_name FROM user WHERE 
first_name LIKE ? AND status=? LIMIT ?,?';
                    $p1 = 1;
                    $p3 = 2;
                    $p4 = 3;
                }
            } else {
                if (isset($userData['last_name'])) {
                    $sql = 'SELECT id, username, first_name, status, last_name FROM user WHERE 
last_name LIKE ? AND status=? LIMIT ?,?';
                    $p2 = 1;
                    $p3 = 2;
                    $p4 = 3;
                } else {
                    $sql = 'SELECT id, username, first_name, status, last_name FROM user WHERE status=? LIMIT ?,?';
                    $p3 = 1;
                    $p4 = 2;
                }
            }
        } else {
            if (isset($userData['first_name'])) {
                if (isset($userData['last_name'])) {
                    $sql = 'SELECT id, username, first_name, status, last_name FROM user WHERE 
first_name LIKE ? AND last_name LIKE ? LIMIT ?,?';
                    $p1 = 1;
                    $p2 = 2;
                    $p4 = 3;
                } else {
                    $sql = 'SELECT id, username, first_name, status, last_name FROM user WHERE 
first_name LIKE ? LIMIT ?,?';
                    $p1 = 1;
                    $p4 = 2;
                }
            } else {
                if (isset($userData['last_name'])) {
                    $sql = 'SELECT id, username, first_name, status, last_name FROM user WHERE 
last_name LIKE ? LIMIT ?,?';
                    $p2 = 1;
                    $p4 = 2;
                } else {
                    $sql = 'SELECT id, username, first_name, status, last_name FROM user LIMIT ?,?';
                    $p4 = 1;
                }
            }
        }

        $p5 = $p4 + 1;

        /*QueryList can't read int in parameter*/
        $connection = $this->getConnection();
        $stmt = $connection->prepare($sql);
        if (isset($userData['first_name'])) $stmt->bindValue($p1, $userData['first_name'] . '%', PDO::PARAM_STR);
        if (isset($userData['last_name'])) $stmt->bindValue($p2, $userData['last_name'] . '%', PDO::PARAM_STR);
        if (isset($userData['status'])) $stmt->bindValue($p3, $userData['status'], PDO::PARAM_STR);
        $stmt->bindValue($p4, $start, PDO::PARAM_INT);
        $stmt->bindValue($p5, $paginationNumber, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll();

        $data['users'] = $rows;
        $data['itemsPerPage'] = $paginationNumber;

        return $data;
    }

    private function filter($userData)
    {
        /*PDO don't have a automatic optional parameter, so...
        The variable $p* are used to follow the relative order of the parameters, this parameters depends of the
        optional parameters in the query*/
        $p1 = 1;
        $p2 = 1;
        $p3 = 1;

        if (isset($userData['status'])) {
            if (isset($userData['first_name'])) {
                if (isset($userData['last_name'])) {
                    $sql = 'SELECT id, username, first_name, status, last_name FROM user WHERE 
first_name LIKE ? AND last_name LIKE ? AND status=?';
                    $p1 = 1;
                    $p2 = 2;
                    $p3 = 3;
                } else {
                    $sql = 'SELECT id, username, first_name, status, last_name FROM user WHERE 
first_name LIKE ? AND status=?';
                    $p1 = 1;
                    $p3 = 2;
                }
            } else {
                if (isset($userData['last_name'])) {
                    $sql = 'SELECT id, username, first_name, status, last_name FROM user WHERE 
last_name LIKE ? AND status=?';
                    $p2 = 1;
                    $p3 = 2;
                } else {
                    $sql = 'SELECT id, username, first_name, status, last_name FROM user WHERE status=?';
                    $p3 = 1;
                }
            }
        } else {
            if (isset($userData['first_name'])) {
                if (isset($userData['last_name'])) {
                    $sql = 'SELECT id, username, first_name, status, last_name FROM user WHERE 
first_name LIKE ? AND last_name LIKE ?';
                    $p1 = 1;
                    $p2 = 2;
                } else {
                    $sql = 'SELECT id, username, first_name, status, last_name FROM user WHERE 
first_name LIKE ?';
                    $p1 = 1;
                }
            } else {
                if (isset($userData['last_name'])) {
                    $sql = 'SELECT id, username, first_name, status, last_name FROM user WHERE 
last_name LIKE ?';
                    $p2 = 1;
                } else {
                    $sql = 'SELECT id, username, first_name, status, last_name FROM user';
                }
            }
        }

        /*QueryList can't read int in parameter*/
        $connection = $this->getConnection();
        $stmt = $connection->prepare($sql);
        if (isset($userData['first_name'])) $stmt->bindValue($p1, $userData['first_name'] . '%', PDO::PARAM_STR);
        if (isset($userData['last_name'])) $stmt->bindValue($p2, $userData['last_name'] . '%', PDO::PARAM_STR);
        if (isset($userData['status'])) $stmt->bindValue($p3, $userData['status'], PDO::PARAM_STR);
        $stmt->execute();
        $rows = $stmt->fetchAll();
        return count($rows);
    }

    public function isAdmin($userData)
    {
        $result = false;
        if (isset($userData['user'])) {
            $roles = $userData['user']->getRol();
            foreach ($roles as $element) {
                if ($element->getId() == 1) {
                    $result = true;
                }
            }
        }

        return $result;
    }
}