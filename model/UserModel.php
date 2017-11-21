<?php

/**
 * Model of User
 *
 */
class UserModel
{

    public $id;
    public $email;
    public $username;
    public $status;
    public $modify;
    public $created;
    public $first_name;
    public $last_name;
    public $rol;
    public $permissions;

    public function __construct($id, $email, $username, $status, $modify, $created, $first_name, $last_name, $rol, $permissions)
    {
        $this->id = $id;
        $this->email = $email;
        $this->username = $username;
        $this->status = $status;
        $this->modify = $modify;
        $this->created = $created;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->rol = $rol;
        $this->permissions = $permissions;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getModify()
    {
        return $this->modify;
    }

    public function getCreate()
    {
        return $this->created;
    }

    public function getFirstName()
    {
        return $this->first_name;
    }

    public function getLastName()
    {
        return $this->last_name;
    }

    public function getRol()
    {
        return $this->rol;
    }

    public function getPermissions()
    {
        return $this->permissions;
    }

}