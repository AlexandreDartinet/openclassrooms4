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
}