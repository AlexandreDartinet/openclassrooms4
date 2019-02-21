<?php
class Post extends DbObject {

    public function __set($name, $value) {
        switch($name) {
            case "id":
                $this->_attributes[$name] = (int) $value;
                break;
            case "date_publication":
                if(self::checkDate($value)) {
                    $this->_attributes[$name] = (string) $value;
                }
                else {
                    throw new Exception("$name n'est pas une date.");
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
                    throw new Exception("$name est vide.");
                }
                break;
            case "content":
                if($value != "") {
                    $this->_attributes[$name] = (string) $value;
                }
                else {
                    throw new Exception("$name est vide.");
                }
                break;
            case "published":
                $this->_attributes[$name] = (boolean) $value;
                break;
            default:
                break;
        }
    }

    public function getAuthor() {
        $userManager = new UserManager();
        $author = $userManager->getUserById($this->id_user);
        return $author;
    }

    public static function default() {
        $post = new self([
            "id" => 0,
            "date_publication" => self::now(),
            "id_user" => 0,
            "title" => "nothing",
            "content" => "nothing",
            "published" => true
        ]);
        return $post;
    }

}