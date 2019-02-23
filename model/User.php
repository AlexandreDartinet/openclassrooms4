<?php
/**
 * Classe représentant une ligne du tableau users de la bdd
 * 
 * @var int LEVEL_ANON : Niveau d'un utilisateur anonyme
 * @var int LEVEL_USER : Niveau d'un utilisateur enregistré
 * @var int LEVEL_MODERATOR : Niveau d'un modérateur
 * @var int LEVEL_EDITOR : Niveau d'un éditeur
 * @var int LEVEL_ADMIN : Niveau d'un administrateur
 * 
 * @var int $id : Identifiant de l'utilisateur
 * @var string $name : Nom de l'utilisateur
 * @var string $password : hash du mot de passe de l'utilisateur
 * @var string $email : Adresse email de l'utilisateur
 * @var string $date_inscription : Date d'inscription de l'utilisateur au format DateTime
 * @var string $last_seen : Date à laquelle l'utilisateur a été vu pour la dernière fois au format DateTime
 * @var int $level : Niveau de l'utilisateur
 * @var string $ip : Ip de l'utilisateur
 * @var string $name_display : Nom à afficher sur le site
 * 
 * @see DbObject : classe parente
 */
class User extends DbObject {

    const LEVEL_ANON = 1;
    const LEVEL_USER = 2;
    const LEVEL_MODERATOR = 3;
    const LEVEL_EDITOR = 4;
    const LEVEL_ADMIN = 5;

    /**
     * Fonction d'encapsulation
     * 
     * @see DbObject->__set(string $name, $value)
     */
    public function __set(string $name, $value) {
        switch($name) {
            case "id":
                $this->_attributes[$name] = (int) $value;
                break;
            case "name":
                if($value != "") {
                    $this->_attributes[$name] = (string) $value;
                }
                else {
                    throw new Exception("User: $name($value) est vide.");
                }
                break;
            case "password":
                if($value != "") {
                    $this->_attributes[$name] = (string) $value;
                }
                else {
                    throw new Exception("User: $name($value) est vide.");
                }
                break;
            case "email":
                if(self::isEmail($value)) {
                    $this->_attributes[$name] = (string) $value;
                }
                else {
                    throw new Exception("User: $name($value) invalide.");
                }
                break;
            case "date_inscription":
                if(self::isDate($value)) {
                    $this->_attributes[$name] = (string) $value;
                }
                else {
                    throw new Exception("User: $name($value) invalide");
                }
                break;
            case "last_seen":
                if(self::isDate($value)) {
                    $this->_attributes[$name] = (string) $value;
                }
                else {
                    throw new Exception("User: $name($value) invalide");
                }
                break;
            case "level":
                $this->_attributes[$name] = (int) $value;
                break;
            case "ip":
                if(self::isIp($value)) {
                    $this->_attributes[$name] = (string) $value;
                }
                else {
                    throw new Exception("User: $name($value) invalide.");
                }
                break;
            case "name_display":
                if($value != "") {
                    $this->_attributes[$name] = (string) $value;
                }
                else {
                    throw new Exception("User: $name($value) invalide.");
                }
                break;
            default:
                throw new Exception("User: $name($value) inconnu.");
                break;
        }
    }

    /**
     * Fonction retournant un objet par défaut
     * 
     * @see DbObject::default()
     */
    public static function default() {
        $user = new self([
            "id" => 0,
            "name" => "Anonyme",
            "password" => "nothing",
            "email" => "nothing@anonymous.fr",
            "date_inscription" => self::now(),
            "last_seen" => self::now(),
            "level" => self::LEVEL_ANON,
            "ip" => $_SERVER["REMOTE_ADDR"],
            "name_display" => "Anonyme"
        ]);
        return $user;
    }
}