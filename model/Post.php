<?php
class Post extends DbObject {

    const EXTRACT_LENGTH = 500;

    public function __set($name, $value) {
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

    public function getAuthor() {
        $userManager = new UserManager();
        $author = $userManager->getUserById($this->id_user);
        return $author;
    }

    public function getExtract() {
        return (strlen($this->content) > self::EXTRACT_LENGTH) ? substr($this->content, 0, self::EXTRACT_LENGTH).'...' : $this->content;
    }

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