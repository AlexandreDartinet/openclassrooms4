<?php

abstract class DbObject {

    protected $_attributes = [];

    public function __construct(array $data) {
        $this->hydrate($data);
    }

    abstract public function __set($name, $value);

    public function __get($name) {
        if (isset($this->_attributes[$name])) {
            return $this->_attributes[$name];
        }
        else {
            throw new Exception("L'attribut $name n'existe pas pour l'objet.");
        }
    }

    public function __isset($name) {
        return isset($this->_attributes[$name]);
    }

    public function hydrate(array $data) {
        foreach ($data as $name => $value) {
            $this->$name = $value;
        }
    }

    abstract public static function default();

    public function rDate($name) {
        return preg_replace('/^(\d{4})-(\d{2})-(\d{2}) (\d{2}:\d{2}:\d{2})$/', '$3/$2/$1 à $4', $this->$name);
    }

    public static function now() {
        return date("Y-m-d H:i:s");
    }
}