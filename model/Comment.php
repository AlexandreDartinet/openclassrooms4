<?php

class Comment extends DbObject {


    public function __set($name, $value) {
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
                    throw new Exception("$name n'est pas une date.");
                }
                break;
            case "ip":
                if(self::isIp($value)) {
                    $this->_attributes[$name] = (string) $value;
                }
                else {
                    throw new Exception("$name n'est pas une ip.");
                }
                break;
            case "name":
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
            case "replies_nbr":
                $this->_attributes[$name] = (int) $value;
                break;
        }
    }

    public function default() {
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
        ])
    }

    public function getName() {
        if($this->id == 0) {
            return $this->name;
        }
        else {
            $userManager = new UserManager();
            $user = $userManager->getUserById($this->id_user);
            return $user->name_display;
        }
    }

}