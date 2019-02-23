<?php

class Recover extends DbObject {
    const HOURS_VALID = "1";

    public function __set($name, $value) {
        switch($name) {
            case "id":
                $this->_attributes[$name] = (int) $value;
                break;
            case "id_user":
                $this->_attributes[$name] = (int) $value;
                break;
            case "recover_key":
                if($value != "") {
                    $this->_attributes[$name] = (string) $value;
                }
                else {
                    throw new Exception("Recover: $name($value) invalide.");
                }
                break;
            case "date_sent":
                if(self::isDate($value)) {
                    $this->_attributes[$name] = (string) $value;
                }
                else {
                    throw new Exception("Recover: $name($value) invalide.");
                }
                break;
            default:
                throw new Exception("Recover: $name($value) attribut inconnu.");
                break;
        }
    }

    public function isValid() {
        $date = new DateTime($this->date_sent);
        $date->add(new DateInterval("PT".self::HOURS_VALID."H"));
        $now = new DateTime("now");
        return $date >= $now;
    }

    public static function default() {
        $recover = new self([
            "id" => 0,
            "id_user" => 0,
            "recover_key" => "nothing",
            "date_sent" => self::now()
        ]);
        return $recover;
    }


}