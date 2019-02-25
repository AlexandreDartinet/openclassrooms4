<?php
/**
 * Classe représentant une ligne du tableau recovers de la bdd
 * 
 * @var string HOURS_VALID : Nombre d'heures pendant lequel un recover est valide
 * @var int $id : Identifiant du recover
 * @var int $id_user : Identifiant de l'utilisateur lié au recover
 * @var string $recover_key : La clé unique du recover
 * @var string $date_sent : Date d'envoi du recover au format DateTime
 * @var User $user : Utilisateur lié au recover
 * 
 * @see DbObject : classe parente
 */
class Recover extends DbObject {

    const HOURS_VALID = "1";

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
            case "user":
                if(is_a($value, 'User')) {
                    $this->_attributes[$name] = $value;
                }
                else {
                    throw new Exception("Recover: $name(".var_export($value).") n'est pas un User.");
                }
                break;
            default:
                throw new Exception("Recover: $name($value) attribut inconnu.");
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
                case "user":
                    if($this->id_user != 0) {
                        $userManager = new UserManager();
                        $user = $userManager->getUserById($this->id_user);
                    }
                    else {
                        $user = User::default();
                    }
                    $this->user = $user;
                    break;
            }
        }
        return parent::__get($name);
    }

    /**
     * Fonction pour déterminer si le recover est encore valable
     * 
     * @return boolean : true si le recover est valable, false sinon
     */
    public function isValid() {
        $date = new DateTime($this->date_sent);
        $date->add(new DateInterval("PT".self::HOURS_VALID."H"));
        $now = new DateTime("now");
        return $date >= $now;
    }

    /**
     * Fonction retournant un objet par défaut
     * 
     * @see DbObject::default()
     */
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