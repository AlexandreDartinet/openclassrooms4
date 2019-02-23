<?php
/**
 * Classe abstraite d'un objet gérant les interactions avec une table de la bdd
 * Ses filles auront accès à un objet $_db dès l'initialisation leur permettant d'interragir avec la bdd
 * 
 * @var PDO $_db : Objet PDO connecté à la bdd
 */
abstract class Manager
{
    protected $_db;

    /**
     * Fonction appelée à l'initialisation de l'objet, initialise l'attribut $_db
     * 
     * @return void
     */
    public function __construct() {
        $this->_db = $this->dbConnect();
    }

    /**
     * Fonction renvoyant un objet PDO connecté à la bdd du site
     * 
     * @return PDO : objet PDO connecté à la bdd du site
     */
    protected function dbConnect()
    {
        try {
            $db = new PDO('mysql:host='.DB_HOST.';dbname='.DB_BASE.';charset=utf8', DB_USER, DB_PASSWORD);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $db->setAttribute(PDO::ATTR_PERSISTENT, true);
            return $db;
        }
        catch (PDOException $e) { // On fait remonter les erreurs PDO
            throw new Exception("Manager: Erreur PDO: ".$e->getMessage());
        }
    }
}