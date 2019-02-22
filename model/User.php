<?php

class User extends DbObject {
    const LEVEL_ANON = 1;
    const LEVEL_USER = 2;
    const LEVEL_MODERATOR = 3;
    const LEVEL_EDITOR = 4;
    const LEVEL_ADMIN = 5;

    public function __construct(array $data) {
        parent::__construct($data);
    }

    public function __set($name, $value) {
        switch($name) {
            case "id":
                $this->_attributes[$name] = (int) $value;
                break;
            case "name":
                if($value != "") {
                    $this->_attributes[$name] = (string) $value;
                }
                else {
                    throw new Exception("L'attribut name est vide.");
                }
                break;
            case "password":
                if($value != "") {
                    $this->_attributes[$name] = (string) $value;
                }
                else {
                    throw new Exception("password est vide.");
                }
                break;
            case "email":
                if(preg_match('/^.+@\w+\.\w+$/', $value)) {
                    $this->_attributes[$name] = (string) $value;
                }
                else {
                    throw new Exception("mail invalide.");
                }
                break;
            case "date_inscription":
                if(self::isDate($value)) {
                    $this->_attributes[$name] = (string) $value;
                }
                else {
                    throw new Exception("Date d'inscription invalide");
                }
                break;
            case "last_seen":
                if(self::isDate($value)) {
                    $this->_attributes[$name] = (string) $value;
                }
                else {
                    throw new Exception("Last seen invalide");
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
                    throw new Exception("ip invalide.");
                }
                break;
            case "name_display":
                if($value != "") {
                    $this->_attributes[$name] = (string) $value;
                }
                else {
                    throw new Exception("Nom d'affichage invalide.");
                }
                break;
            default:
                break;
        }
    }

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