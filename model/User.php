<?php

class User extends DbObject {
    const LEVEL_ANON = 1;
    const LEVEL_ADMIN = 5;

    public function __construct(array $data) {
        parent::__construct($data);
    }

    public function __set($name, $value) {
        switch($name) {
            case "id":
                if(is_int($value)) {
                    $this->_attribute[$name] = $value;
                }
                else {
                    throw new Exception("L'id n'est pas un entier.");
                }
                break;
            case "name":
                if(is_string($value) && $value != "") {
                    $this->_attribute[$name] = $value;
                }
                else {
                    throw new Exception("L'attribut name n'est pas une chaine de caractères.");
                }
                break;
            case "password":
                if(is_string($value)) {
                    $this->_attribute[$name] = $value;
                }
                else {
                    throw new Exception("password n'est pas une chaine de caractères.");
                }
                break;
            case "mail":
                if(preg_match('/^\w+@\w+\.\w+$/', $value)) {
                    $this->_attribute[$name] = $value;
                }
                else {
                    throw new Exception("mail invalide.");
                }
                break;
            case "date_inscription":
                if(preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $value)) {
                    $this->_attribute[$name] = $value;
                }
                else {
                    throw new Exception("Date d'inscription invalide");
                }
                break;
            case "last_seen":
                if(preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $value)) {
                    $this->_attribute[$name] = $value;
                }
                else {
                    throw new Exception("Last seen invalide");
                }
                break;
            case "level":
                if(is_int($value)) {
                    $this->_attribute[$name] = $value;
                }
                else {
                    throw new Exception("level invalide");
                }
                break;
            case "ip":
                if(preg_match('/^\d{1,3}(\.\d{1,3}){3}$/', $value)) {
                    $this->_attribute[$name] = $value;
                }
                else {
                    throw new Exception("ip invalide.");
                }
                break;
            case "name_display":
                if(is_string($value) && $value != "") {
                    $this->_attribute[$name] = $value;
                }
                else {
                    throw new Exception("Nom d'affichage invalide.");
                }
                break;
            default:
                throw new Exception("Attribut $name inconnu.");
                break;
        }
    }
}