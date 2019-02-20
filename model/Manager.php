<?php


abstract class Manager
{
    protected $_db;

    public function __constructor() {
        $this->_db = $this->dbConnect();
    }

    protected function dbConnect()
    {
        $db = new PDO('mysql:host='.DB_HOST.';dbname='.DB_BASE.';charset=utf8', DB_USER, DB_PASSWORD);
        return $db;
    }
}