<?php
class Post extends DbObject {

    public function __set($name, $value) {
        $this->_attributes[$name] = $value;
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