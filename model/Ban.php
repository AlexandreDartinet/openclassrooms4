<?php
namespace DartAlex;
/**
 * Classe représentant une ligne du tableau bans de la bdd
 * 
 * @var int TYPE_COMMENT : Code du ban commentaires
 * @var int TYPE_ALL : Code du ban total
 * @var array TYPES : Correspondance code => nom
 * @var int $id : Identifiant du ban
 * @var string $ip : Ip sur laquelle appliquer le ban
 * @var int $type : Type du ban
 * @var string $content : Commentaire du ban
 * 
 * @see DbObject : classe parente
 */
class Ban extends DbObject {

    const TYPE_COMMENT = 1;
    const TYPE_ALL = 10;
    const TYPES = [
        self::TYPE_COMMENT => "Commentaires",
        self::TYPE_ALL => "Total"
    ];

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
            case "ip":
                if(self::isIp($value)) {
                    $this->_attributes[$name] = (string) $value;
                }
                else {
                    throw new \Exception("Ban: $name($value) n'est pas une ip.");
                }
                break;
            case "date_ban":
                if(self::isDate($value)) {
                    $this->_attributes[$name] = (string) $value;
                }
                else {
                    throw new \Exception("Ban: $name($value) n'est pas une date.");
                }
                break;
            case "type":
                $this->_attributes[$name] = (int) $value;
                break;
            case "content":
                if($value != "") {
                    $this->_attributes[$name] = (string) $value;
                }
                else {
                    throw new \Exception("Ban: $name($value) vide.");
                }
                break;
            case "manager":
                if(is_a($value, 'DartAlex\\BanManager')) {
                    $this->_attributes[$name] = $value;
                }
                else {
                    throw new \Exception("Ban: $name(".var_export($value).") n'est pas un BanManager.");
                }
                break;
            default:
                throw new \Exception("Ban: $name($value) attribut inconnu.");
                break;
        }
    }

    /**
     * Cette fonction est appelée lorsqu'on appelle $objet->$name pour retourner les attributs de l'objet.
     * Instancie dynamiquement les objets si ils ne le sont pas déjà.
     * 
     * @param string $name : Nom de l'attribut à retourner
     * 
     * @return mixed : Dépend de l'attribut qu'on a demandé
     * 
     * @see DbObject::__get()
     */
    public function __get(string $name) {
        if(!isset($this->$name)) {
            switch($name) {
                case "manager":
                    $this->$name = new BanManager();
                    break;
            }
        }
        return parent::__get($name);
    }

    /**
     * @see DbObject::save()
     */
    public function save() {
        return $this->manager->setBan($this);
    }

    /**
     * @see DbObject::delete()
     */
    public function delete() {
        return $this->manager->removeBy('id', $this->id);
    }

    /**
     * Fonction retournant un objet par défaut
     * 
     * @see DbObject::default()
     */
    public static function default() {
        $ban = new self([
            "id" => 0,
            "ip" => "0.0.0.0",
            "date_ban" => self::now(),
            "type" => self::TYPE_COMMENT,
            "content" => "nothing"
        ]);
        return $ban;
    }
}