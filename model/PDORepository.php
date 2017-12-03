<?php

abstract class PDORepository extends PDO{

    const USERNAME = "root";
    const PASSWORD = "";
	  const HOST ="localhost";
	  const DB = "taller";

    private $connection;

    public function getConnection(){

        $u=self::USERNAME;
        $p=self::PASSWORD;
        $db=self::DB;
        $host=self::HOST;
        $connection = new PDO ("mysql:dbname=$db;host=$host;names='utf8'", $u, $p);

        return $connection;
    }
}
