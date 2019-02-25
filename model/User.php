<?php
/**
 * Classe représentant une ligne du tableau users de la bdd
 * 
 * @var int LEVEL_ANON : Niveau d'un utilisateur anonyme
 * @var int LEVEL_USER : Niveau d'un utilisateur enregistré
 * @var int LEVEL_MODERATOR : Niveau d'un modérateur
 * @var int LEVEL_EDITOR : Niveau d'un éditeur
 * @var int LEVEL_ADMIN : Niveau d'un administrateur
 * @var array LEVELS : Tableau représentant tous les niveaux
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
 * @var int $comments_nbr : Nombre de commentaires par l'utilisateur
 * @var int $posts_nbr : Nombre de posts par l'utilisateur
 * @var int $reports_nbr : Nombre de signalements par l'utilisateur
 * @var array $comments : Commentaires postés par l'utilisateur
 * @var array $posts : Posts publiés par l'utilisateur
 * @var array $reports : Signalements envoyés par l'utilisateur
 * @var UserManager $manager : UserManager
 * 
 * @see DbObject : classe parente
 */
class User extends DbObject {

    const LEVEL_ANON = 1;
    const LEVEL_USER = 2;
    const LEVEL_MODERATOR = 3;
    const LEVEL_EDITOR = 4;
    const LEVEL_ADMIN = 5;
    const LEVELS = [
        self::LEVEL_ANON => "Anonyme",
        self::LEVEL_USER => "Utilisateur",
        self::LEVEL_MODERATOR => "Modérateur",
        self::LEVEL_EDITOR => "Éditeur",
        self::LEVEL_ADMIN => "Administrateur"
    ];

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
            case "comments_nbr":
                $this->_attributes[$name] = (int) $value;
                break;
            case "posts_nbr":
                $this->_attributes[$name] = (int) $value;
                break;
            case "reports_nbr":
                $this->_attributes[$name] = (int) $value;
                break;
            case "comments":
                if(is_array($value)) {
                    $this->_attributes[$name] = $value;
                }
                else {
                    throw new Exception("User: $name(".var_export($value).") n'est pas un Array.");
                }
                break;
            case "posts":
                if(is_array($value)) {
                    $this->_attributes[$name] = $value;
                }
                else {
                    throw new Exception("User: $name(".var_export($value).") n'est pas un Array.");
                }
                break;
            case "reports":
                if(is_array($value)) {
                    $this->_attributes[$name] = $value;
                }
                else {
                    throw new Exception("User: $name(".var_export($value).") n'est pas un Array.");
                }
                break;
            case "manager":
                if(is_a($value, 'UserManager')) {
                    $this->_attributes[$name] = $value;
                }
                else {
                    throw new Exception("User: $name(".var_export($value).") n'est pas un UserManager.");
                }
                break;
            default:
                throw new Exception("User: $name($value) inconnu.");
                break;
        }
    }

    /**
     * Cette fonction est appelée lorsqu'on appelle $objet->$name pour retourner les attributs de l'objet.
     * Instancie dynamiquement les objets si ils ne le sont pas déjà.
     * 
     * @param string $name : Nom de l'attribut à retourner
     * 
     * @return mixed : Dépend de l'attribut qu'on a demandé
     * 
     * @see DbObject::__get()
     */
    public function __get(string $name) {
        if(!isset($this->$name)) {
            switch($name) {
                case "comments_nbr":
                    if(isset($this->comments)) {
                        $count = sizeof($this->comments);
                    }
                    else {
                        $commentManager = new CommentManager();
                        $count = $commentManager->countCommentsByUser($this);
                    }
                    $this->$name = $count;
                    break;
                case "posts_nbr":
                    if(isset($this->posts)) {
                        $count = sizeof($this->posts);
                    }
                    else {
                        $postManager = new PostManager();
                        $count = $postManager->countPostsByUser($this, false);
                    }
                    $this->$name = $count;
                    break;
                case "reports_nbr":
                    if(isset($this->reports)) {
                        $count = sizeof($this->reports);
                    }
                    else {
                        $reportManager = new ReportManager();
                        $count = $reportManager->count('id_user', $this->id);
                    }
                    $this->$name = $count;
                    break;
                case "comments":
                    $commentManager = new CommentManager();
                    $comments = $commentManager->getCommentsByUser($this);
                    $this->$name = $comments;
                    break;
                case "posts":
                    $postManager = new PostManager();
                    $posts = $postManager->getPostsByUser($this);
                    $this->$name = $posts;
                    break;
                case "reports":
                    $reportManager = new ReportManager();
                    $reports = $reportManager->getReportsByUser($this);
                    $this->$name = $reports;
                    break;
                case "manager":
                    $this->$name = new UserManager();
                    break;
            }
        }
        return parent::__get($name);
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
        if($this->id == 0) {
            return "<a class='no-profile-link'>".htmlspecialchars($this->name_display)."</a>";
        }   
        else {
            return "<a class='profile-link profile-link-$this->id' href='/profile/$this->id/'>".htmlspecialchars($this->name_display)."</a>";
        }
    }

    /**
     * Transforme un niveau en chaine lisible
     * 
     * @param int $level : Niveau que l'on souhaite afficher
     * 
     * @return string : Niveau lisible
     */
    public static function levelToText(int $level) {
        return (isset(self::LEVELS[$level])?self::LEVELS[$level]:"Inconnu");
    }

    /**
     * @see DbObject::save()
     */
    public function save() {
        return $this->manager->setUser($this);
    }

    /**
     * @see DbObject::delete()
     */
    public function delete() {
        $postManager = new PostManager();
        $postManager->removeUser($this);
        $commentManager = new CommentManager();
        $commentManager->removeUser($this);
        $reportManager = new ReportManager();
        $reportManager->removeUser($this);
        return $this->manager->removeBy('id',$this->id);
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