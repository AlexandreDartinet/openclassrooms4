<?php


abstract class Manager
{
    protected $_db;

    public function __construct() {
        $this->_db = $this->dbConnect();
    }

    protected function dbConnect()
    {
        try {
            $db = new PDO('mysql:host='.DB_HOST.';dbname='.DB_BASE.';charset=utf8', DB_USER, DB_PASSWORD);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $db->setAttribute(PDO::ATTR_PERSISTENT, true);
            return $db;
        }
        catch (PDOException $e) {
            throw new Exception("Erreur PDO : ".$e->getMessage());
        }
    }
}