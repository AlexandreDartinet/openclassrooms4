<?php
/**
 * Classe représentant une ligne du tableau comments de la bdd
 * 
 * @var int $id : Identifiant du commentaire
 * @var int $id_post : Identifiant du post auquel le commentaire est lié
 * @var int $id_user : Identifiant de l'utilisateur qui a envoyé le commentaire (0 si anonyme)
 * @var int $reply_to : Identifiant du commentaire auquel ce commentaire est lié (0 si aucun)
 * @var string $date_publication : Date de publication au format DateTime
 * @var string $ip : Ip qui a posté ce commentaire
 * @var string $name : Nom de l'auteur du commentaire
 * @var string $content : Corps du commentaire
 * @var int $replies_nbr : Nombre calculé de réponses à ce commentaire
 * @var int $reports_nbr : Nombre de signalements à ce commentaire
 * @var User $user : Utilisateur associé au commentaire
 * @var Post $post : Post associé au commentaire
 * @var array $replies : Tableau de commentaires qui sont des réponses à ce commentaire
 * @var array $reports : Tableau des signalement de ce commentaire
 * 
 * @see DbObject : classe parente
 */
class Comment extends DbObject {

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
            case "id_post":
                $this->_attributes[$name] = (int) $value;
                break;
            case "id_user":
                $this->_attributes[$name] = (int) $value;
                break;
            case "reply_to":
                $this->_attributes[$name] = (int) $value;
                break;
            case "date_publication":
                if(self::isDate($value)) {
                    $this->_attributes[$name] = (string) $value;
                }
                else {
                    throw new Exception("Comment: $name($value) n'est pas une date.");
                }
                break;
            case "ip":
                if(self::isIp($value)) {
                    $this->_attributes[$name] = (string) $value;
                }
                else {
                    throw new Exception("Comment: $name($value) n'est pas une ip.");
                }
                break;
            case "name":
                if($value != "") {
                    $this->_attributes[$name] = (string) $value;
                }
                else {
                    throw new Exception("Comment: $name($value) est vide.");
                }
                break;
            case "content":
                if($value != "") {
                    $this->_attributes[$name] = (string) $value;
                }
                else {
                    throw new Exception("Comment: $name($value) est vide.");
                }
                break;
            case "replies_nbr":
                $this->_attributes[$name] = (int) $value;
                break;
            case "reports_nbr":
                $this->_attributes[$name] = (int) $value;
                break;
            case "user":
                if(is_a($value, 'User')) {
                    $this->_attributes[$name] = $value;
                }
                else {
                    throw new Exception("Comment: $name(".var_export($value).") n'est pas un User.");
                }
                break;
            case "post":
                if(is_a($value, 'Post')) {
                    $this->_attributes[$name] = $value;
                }
                else {
                    throw new Exception("Comment: $name(".var_export($value).") n'est pas un Post.");
                }
                break;
            case "replies":
                if(is_array($value)) {
                    $this->_attributes[$name] = $value;
                }
                else {
                    throw new Exception("Comment: $name(".var_export($value).") n'est pas un Array.");
                }
                break;
            case "reports":
                if(is_array($value)) {
                    $this->_attributes[$name] = $value;
                }
                else {
                    throw new Exception("Comment: $name(".var_export($value).") n'est pas un Array.");
                }
                break;
            default:
                throw new Exception("Comment: $name($value) attribut inconnu.");
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
                case "user":
                    if($this->id_user != 0) {
                        $userManager = new UserManager();
                        $user = $userManager->getUserById($this->id_user);
                    }
                    else {
                        $user = User::default();
                    }
                    $this->user = $user;
                    break;
                case "post":
                    $postManager = new PostManager();
                    $post = $postManager->getPostById($this->id_post);
                    $this->post = $post;
                    break;
                case "replies":
                    $commentManager = new CommentManager();
                    $replies = $commentManager->getReplies($this);
                    $this->replies = $replies;
                    break;
                case "reports":
                    $reportManager = new ReportManager();
                    $reports = $reportManager->getReports($this->id, "all");
                    $this->reports = $reports;
                    break;
                case "reports_nbr":
                    if(isset($this->reports)) {
                        $this->reports_nbr = sizeof($this->reports);
                    }
                    else {
                        $reportManager = new ReportManager();
                        $this->reports_nbr = $reportManager->count('id_comment', $this->id);
                    }
                    break;
            }
        }
        return parent::__get($name);
    }


    /**
     * Fonction retournant un objet par défaut
     * 
     * @see DbObject::default()
     */
    public static function default() {
        $comment = new self([
            "id" => 0,
            "id_post" => 0,
            "id_user" => 0,
            "reply_to" => 0,
            "date_publication" => self::now(),
            "ip" => $_SERVER['REMOTE_ADDR'],
            "name" => "Anonyme",
            "content" => "nothing",
            "replies_nbr" => 0
        ]);
        return $comment;
    }

    /**
     * Fonction retournant le nom à afficher pour un commentaire.
     * L'attribut name du commentaire si l'utilisateur était anonyme, sinon le name_display de l'utilisateur.
     * 
     * @return string : Nom de l'auteur du commentaire.
     */
    public function getName() {
        if($this->id_user == 0) {
            return $this->name;
        }
        else { // Si l'auteur n'est pas anonyme, on récupère son user pour retourner son nom
            return $this->user->name_display;
        }
    }

    /**
     * Fonction retournant le lien vers le profil de l'auteur du commentaire, si le profil existe
     * 
     * @return string : Lien vers le profil
     */
    public function displayName() {
        if($this->id_user == 0) {
            return htmlspecialchars($this->name);
        }
        else {
            return $this->user->displayName();
        }
    }
    
    /**
     * Fonction déterminant si un utilisateur peut éditer un commentaire.
     * @param User $user : L'utilisateur à tester
     * 
     * @return boolean : True si l'utilisateur peut éditer le commentaire
     */
    public function canEdit(User $user) {
        if($user->id == 0) {
            if($user->ip == $this->ip) {
                return true;
            }
        }
        else {
            if($user->id == $this->id_user) {
                return true;
            }
        }
        return false;
    }

}