<?php
/**
 * Classe abstraite d'un objet représentant une ligne d'une table dans la bdd
 * 
 * @var array $_attributes : Tableau contenant les attributs de l'objet
 */
abstract class DbObject {

    protected $_attributes = [];

    /**
     * A l'initialisation de l'objet, on veut un array contenant les attributs de l'objet pour l'hydrater.
     * 
     * @param array $data : Le tableau correspondant aux attributs de l'objet
     * 
     * @return void
     */
    public function __construct(array $data) {
        $this->hydrate($data);
    }

    /**
     * On s'assure que chaque classe fille aura cette fonction, appelée lorsqu'on écrira $objet->$name = $value pour vérifier la validité de chaque attribut.
     * 
     * @param string $name : Nom de l'attribut
     * @param $value : valeur de l'attribut
     * 
     * @return void
     */
    abstract public function __set(string $name, $value);

    /**
     * Cette fonction est appelée lorsqu'on appelle $objet->$name pour retourner les attributs de l'objet.
     * 
     * @param string $name : Nom de l'attribut à retourner
     * 
     * @return mixed : Dépend de l'attribut qu'on a demandé
     */
    public function __get(string $name) {
        if (isset($this->_attributes[$name])) {
            return $this->_attributes[$name];
        }
        else {
            throw new Exception("DbObject: L'attribut $name n'existe pas pour l'objet.");
            return false;
        };
    }

    /**
     * Fonction appelée lorsqu'on va appeler isset($objet->$name)
     * 
     * @param string $name : Nom de l'attribut
     * 
     * @return boolean : true si l'attribut existe, false sinon
     */
    public function __isset(string $name) {
        return isset($this->_attributes[$name]);
    }

    /**
     * Fonction d'hydratation de l'objet
     * 
     * @param array $data : Tableau d'attributs de l'objet
     * 
     * @return void
     */
    public function hydrate(array $data) {
        foreach ($data as $name => $value) {
            if(!is_int($name)) {
                $this->$name = $value;
            }
        }
    }

    /**
     * On s'assure que les classes fille auront une fonction save() pour enregistrer l'objet dans la bdd
     * 
     * @return boolean : true si la requête s'est exécutée avec succès
     */
    abstract public function save();

    /**
     * On s'assure que les classes filles auront une fonction delete() pour supprimer l'objet de la bdd
     * 
     * @return boolean : true si la requête s'est exécutée avec succès
     */
    abstract public function delete();

    /**
     * On s'assure que les classes filles auront une fonction statique default() pour retourner un objet par défaut de la classe.
     * 
     * @return Object : Objet par défaut de la classe dont la fonction a été appelée
     */
    abstract public static function default();

    /**
     * Fonction pour retourner une date lisible française à partir d'un attribut de l'objet
     * 
     * @param string $name : Nom de l'attribut à convertir en date lisible
     * 
     * @return string : Date lisible par un français
     */
    public function rDate(string $name) {
        return preg_replace('/^(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})$/', '$3/$2/$1 à $4h$5', $this->$name);
    }

    /**
     * Fonction statique pour tester si une chaine de caractères est au dormat DateTime
     * 
     * @param string $date : La chaine de caractères qu'on veut tester
     * 
     * @return boolean : true si $date est une date, false sinon
     */
    public static function isDate(string $date) {
        return preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $date);
    }

    /**
     * Fonction statique pour tester si une chaine de caractères est une ip
     * 
     * @param string $ip : La chaine de caractères à tester
     * 
     * @return boolean : true si $ip est une ip, false sinon
     */
    public static function isIp(string $ip) {
        return preg_match('/^\d{1,3}(\.\d{1,3}){3}$/', $ip);
    }

    /**
     * Fonction statique pour tester si une chaine de caractères est une adresse email
     * 
     * @param string $email : La chaine de caractères à tester
     * 
     * @return boolean : true si $email est une adresse email, false sinon
     */
    public static function isEmail(string $email) {
        return preg_match('/^.+@.+\.\w+$/', $email);
    }

    /**
     * Fonction statique pour retourner une chaine de caractères au format DateTime représentant la date et l'heure actuelle
     * 
     * @return string : La chaine représentant la date et heure actuelle
     */
    public static function now() {
        return date("Y-m-d H:i:s");
    }
    
    /**
     * Fonction statique pour retourner une chaine lisible de la data actuelle
     * 
     * @return string : Chaine lisible représentant la date et l'heure actuelle
     */
    public static function rNow() {
        return preg_replace('/^(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})$/', '$3/$2/$1 à $4h$5', self::now());
    }

    /**
     * Fonction statique pour retourner une date datetime html à partir d'une date DateTime
     * 
     * @return string
     */
    public static function dateToHtml(string $date) {
        return preg_replace('/^(\d{4}-\d{2}-\d{2}) (\d{2}:\d{2}):\d{2}$/', '$1T$2', $date);
    }

    /**
     * Fonction statique pour retourner une date DateTime à partir d'une date html
     * 
     * @return string
     */
    public static function htmlToDate(string $date) {
        return preg_replace('/^(\d{4}-\d{2}-\d{2})T(\d{2}:\d{2})$/', '$1 $2:00', $date);
    }
}