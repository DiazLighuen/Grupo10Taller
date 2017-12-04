<?php

/**
 * Model of User
 *
 */
class UserModel
{

    public $id;
    public $username;
    public $status;
    public $name;
    public $permisions;

    public function __construct($id, $username, $status, $name, $permisions)
    {
        $this->id = $id;
        $this->username = $username;
        $this->status = $status;
        $this->name = $name;
        $this->permisions = $permisions;
    }


    public function getId()
    {
        return $this->id;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getPermisions()
    {
        return $this->permisions;
    }

}