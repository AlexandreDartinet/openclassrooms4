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
     * Cette fonction est appelée lorsqu'on appelle $objet->$name pour retourner les attributs de l'objet.
     * 
     * @param string $name : Nom de l'attribut à retourner
     * 
     * @return mixed : Dépend de l'attribut qu'on a demandé
     */
    public function __get(string $name) {
        if (isset($this->_attributes[$name])) {
            return $this->_attributes[$name];
        }
        else {
            throw new Exception("User: L'attribut $name n'existe pas pour l'objet.");
        }
    }

    /**
     * Retourne le nombre de commentaires postés par l'utilisateur
     * 
     * @return int : Nombre de commentaires
     */
    public function getCommentsNbr() {
        $commentManager = new CommentManager();
        return (int) $commentManager->countCommentsByUser($this);
    }

    /**
     * Retourne le nombre de posts publiés par l'utilisateur
     * 
     * @param boolean $published : true si on ne veut que les posts publiés
     * 
     * @return int : Nombre de commentaires
     */
    public function getPostsNbr($published = true) {
        $postManager = new PostManager();
        return (int) $postManager->countPostsByUser($this, $published);
    }

    /**
     * Retourne le niveau de l'utilisateur sous forme lisible
     * 
     * @return string : Niveau lisible de l'utilisateur
     */
    public function displayLevel() {
        return self::levelToText($this->level);
    }

    /**
     * Retourne le nom d'un utilisateur avec un lien vers son profil
     * 
     * @return string : Lien vers le profil de l'utilisateur
     */
    public function displayName() {

    }

    /**
     * Transforme un niveau en chaine lisible
     * 
     * @param int $level : Niveau que l'on souhaite afficher
     * 
     * @return string : Niveau lisible
     */
    public static function levelToText(int $level) {
        switch($level) {
            case self::LEVEL_ANON:
                return "Anonyme";
                break;
            case self::LEVEL_USER:
                return "Utilisateur";
                break;
            case self::LEVEL_MODERATOR:
                return "Modérateur";
                break;
            case self::LEVEL_EDITOR:
                return "Éditeur";
                break;
            case self::LEVEL_ADMIN:
                return "Administrateur";
                break;
            default:
                return "Inconnu";
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