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
            default:
                throw new Exception("Post: $name($value) attribut inconnu.");
                break;
        }
    }

    /**
     * Retourne l'User correspondant à l'auteur du post
     * 
     * @return User : Auteur du post
     */
    public function getAuthor() {
        $userManager = new UserManager();
        $author = $userManager->getUserById($this->id_user);
        return $author;
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