<?php
/**
 * Classe abstraite d'un objet gérant les interactions avec une table de la bdd
 * Ses filles auront accès à un objet $_db dès l'initialisation leur permettant d'interragir avec la bdd
 * 
 * @var PDO $_db : Objet PDO connecté à la bdd
 * @var string TABLE_NAME : Nom de la table, abstract pour forcer les classes filles à la déclarer
 */
abstract class Manager
{
    protected $_db;
    const TABLE_NAME = 'abstract';

    /**
     * Fonction appelée à l'initialisation de l'objet, initialise l'attribut $_db
     * 
     * @return void
     */
    public function __construct() {
        $this->_db = $this->dbConnect();
        if(static::TABLE_NAME == 'abstract') {
            throw new Exception("Manager: TABLE_NAME non déclaré.");
        }
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

    /**
     * Vérifie s'il existe des lignes dans la table ou le champ $name est égal à $value
     * 
     * @param string $name : Nom du champ qu'on veut tester
     * @param mixed $value : Valeur avec laquelle on veut tester le champ
     * 
     * @return boolean : true si une ou plusieurs lignes existent
     */
    public function exists(string $name, $value) {
        $count = $this->count($name, $value);
        if($count == 0) {
            return false;
        }
        else {
            return true;
        }
    }

    /**
     * Supprime des données de la bdd ou le champ $name est égal à $value
     * 
     * @param string $name : Nom du champ qu'on veut tester
     * @param mixed $value : Valeur avec laquelle on veut tester le champ
     * 
     * @return boolean : true si exécuté avec succès
     */
    public function removeBy(string $name, $value) {
        $req = $this->_db->prepare("DELETE FROM ".'`'.static::TABLE_NAME.'`'." WHERE `$name`=?");
        return $req->execute([$value]);
    }

    /**
     * Compte les lignes de la table, optionnellement avec un test sur le champ $name avec $value
     * 
     * @param string $name : Nom du champ à tester (optionnel)
     * @param mixed $value : Valeur avec laquelle on veut tester le champ (optionnel)
     * 
     * @return int : Nombre de lignes
     */
    public function count($name = 'noQuery', $value = 'noQuery') {
        if($name == 'noQuery') {
            $req = $this->_db->prepare("SELECT COUNT(*) AS count FROM ".'`'.static::TABLE_NAME.'`'."");
        }
        else {
            $req = $this->_db->prepare("SELECT COUNT(*) AS count FROM ".'`'.static::TABLE_NAME.'`'." WHERE `$name`=:value");
            $req->bindParam(':value', $value);
        }
        if($req->execute()) {
            if($res = $req->fetch()) {
                $count = (int) $res['count'];
            }
            else {
                $count = 0;
            }
            $req->closeCursor();
            return $count;
        }
        else {
            throw new Exception("DbObject: Erreur de requête count($name, $value).");
        }
    }
    
    /**
     * Retourne une requête à partir d'un test de champs
     * 
     * @param string $name : Nom du champ à tester
     * @param mixed $value : Valeur avec laquelle on teste le champ
     * 
     * @return PDOStatement : Requête correspondante
     */
    protected function getBy(string $name, $value) {
        $req = $this->_db->prepare("SELECT * FROM ".'`'.static::TABLE_NAME.'`'." WHERE `$name`=?");
        if($req->execute([$value])) {
            return $req;
        }
        else {
            // throw new Exception("DbObject: getBy erreur dans la requête \$name($name) \$value($value).");
            return false;
        }
    }
}