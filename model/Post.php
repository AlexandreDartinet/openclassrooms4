<?php
/**
 * Classe représentant une ligne du tableau posts dans la bdd
 * 
 * @var int EXTRACT_LENGTH : La longueur par défaut des extraits de posts
 * @var int $id : Identifiant du post
 * @var string $date_publication : Date de publication du post au format DateTime
 * @var int $id_user : Identifiant de l'utilisateur auteur du post
 * @var string $title : Titre du post
 * @var string $content : Contenu du post
 * @var boolean $published : Est-ce que l'article est publié
 * @var int $comments_nbr : Nombre calculé de commentaires liés au post
 * @var User $user : Utilisateur associé au post
 * @var array $comments : Tous les commentaires associés à un post
 * 
 * @see DbObject : classe parente
 */
class Post extends DbObject {

    const EXTRACT_LENGTH = 500;
    
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
            case "date_publication":
                if(self::isDate($value)) {
                    $this->_attributes[$name] = (string) $value;
                }
                else {
                    throw new Exception("Post: $name($value) n'est pas une date.");
                }
                break;
            case "id_user":
                $this->_attributes[$name] = (int) $value;
                break;
            case "title":
                if($value != "") {
                    $this->_attributes[$name] = (string) $value;
                }
                else {
                    throw new Exception("Post: $name($value) est vide.");
                }
                break;
            case "content":
                if($value != "") {
                    $this->_attributes[$name] = (string) $value;
                }
                else {
                    throw new Exception("Post: $name($value) est vide.");
                }
                break;
            case "published":
                $this->_attributes[$name] = (boolean) $value;
                break;
            case "comments_nbr":
                $this->_attributes[$name] = (int) $value;
                break;
            case "user":
                if(is_a($value, 'User')) {
                    $this->_attributes[$name] = $value; 
                }
                else {
                    throw new Exception("Post: $name(".var_export($value).") n'est pas un User.");
                }
                break;
            case "comments":
                if(is_array($value)) {
                    $this->_attributes[$name] = $value; 
                }
                else {
                    throw new Exception("Post: $name(".var_export($value).") n'est pas un array.");
                }
                break;
            default:
                throw new Exception("Post: $name($value) attribut inconnu.");
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
                    $this->$name = $user;
                    break;
                case "comments":
                    $commentManager = new CommentManager();
                    $this->$name = $commentManager->getComments($this->id, "all");
                    break;
            }
        }
        return parent::__get($name);
    }

    /**
     * Retourne un extrait du post de longueur définie par EXTRACT_LENGTH
     * 
     * @return string : Extrait du post
     */
    public function getExtract() {
        return (strlen($this->content) > self::EXTRACT_LENGTH) ? substr($this->content, 0, self::EXTRACT_LENGTH).'...' : $this->content;
    }

    /**
     * Fonction retournant un objet par défaut
     * 
     * @see DbObject::default()
     */
    public static function default() {
        $post = new self([
            "id" => 0,
            "date_publication" => self::now(),
            "id_user" => 0,
            "title" => "nothing",
            "content" => "nothing",
            "published" => true,
            "comments_nbr" => 0
        ]);
        return $post;
    }

}