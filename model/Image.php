<?php
/**
 * Classe représentant une ligne du tableau images de la bdd
 * 
 * @var string PATH : Ou les images sont enregistrées sur le disque
 * @var string EXT : Extension sous laquelle les images sont enregistrées
 * @var array URL : Url par défaut pour chaque type d'image
 * @var int TYPE_POST : Type des images pour les posts
 * @var int TYPE_AVATAR : Type des images pour les avatars
 * @var array TYPES : Tableau des types
 * @var int $id : Identifiant du image
 * @var int $id_user : Identifiant de l'utilisateur lié au image
 * @var string $date_sent : Date d'envoi du image au format DateTime
 * @var string $file_name : Fichier contenant l'image
 * @var int $type : Type de l'image
 * @var string $title : Titre de l'image
 * @var string $url : Url de l'image
 * @var User $user : Utilisateur lié au image
 * @var ImageManager $manager : ImageManager
 * 
 * @see DbObject : classe parente
 */
class Image extends DbObject {

    const PATH = "private/images/";
    const EXT = ".png";
    const URL = [
        self::TYPE_POST => "https://".SITE_URL."/generated/image/",
        self::TYPE_AVATAR => "https://".SITE_URL.'/generated/avatar/'
    ];
    const TYPE_POST = 1;
    const TYPE_AVATAR = 2;
    const TYPES = [
        self::TYPE_POST => "Image pour les posts du blog",
        self::TYPE_AVATAR => "Avatar d'un utilisateur"
    ]

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
            case "file_name":
                if($value != "") {
                    $this->_attributes[$name] = (string) $value;
                }
                else {
                    throw new Exception("Image: $name($value) invalide.");
                }
                break;
            case "date_sent":
                if(self::isDate($value)) {
                    $this->_attributes[$name] = (string) $value;
                }
                else {
                    throw new Exception("Image: $name($value) invalide.");
                }
                break;
            case "type":
                $this->_attributes[$name] = (int) $value;
                break;
            case "title":
                if($value != "") {
                    $this->_attributes[$name] = (string) $value;
                }
                else {
                    throw new Exception("Image: $name($value) invalide.");
                }
                break;
            case "url":
                if($value != "") {
                    $this->_attributes[$name] = (string) $value;
                }
                else {
                    throw new Exception("Image: $name($value) invalide.");
                }
                break;
            case "image":
                $this->_attributes[$name] = $value;
                break;
            case "user":
                if(is_a($value, 'User')) {
                    $this->_attributes["id_user"] = $value->id;
                    $this->_attributes[$name] = $value;
                }
                else {
                    throw new Exception("Image: $name(".var_export($value).") n'est pas un User.");
                }
                break;
            case "manager":
                if(is_a($value, 'ImageManager')) {
                    $this->_attributes[$name] = $value;
                }
                else {
                    throw new Exception("Image: $name(".var_export($value).") n'est pas un ImageManager.");
                }
                break;
            default:
                throw new Exception("Image: $name($value) attribut inconnu.");
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
                case "url":
                    if($this->type == self::TYPE_AVATAR) {
                        $this->$name = self::URL[$this->type].$this->id_user.self::EXT;
                    }
                    else {
                        $this->$name = self::URL[$this->type].$this->file_name.self::EXT;
                    }
                    break;
                case "image":
                    $this->$name = imagecreatefrompng(self::PATH.$this->file_name.self::EXT);
                    break;
                case "user":
                    if($this->id_user != 0) {
                        $userManager = new UserManager();
                        $user = $userManager->getUserById($this->id_user);
                    }
                    else {
                        $user = User::default();
                    }
                    $this->$name = $user;
                    break;
                case "manager":
                    $this->$name = new ImageManager();
                    break;
            }
        }
        return parent::__get($name);
    }

    /**
     * @see DbObject::save()
     */
    public function save() {
        imagepng($this->image, self::PATH.$this->file_name.self::EXT);
        return $this->manager->setImage($this);
    }

    /**
     * @see DbObject::delete()
     */
    public function delete() {
        $removed = unlink(self::PATH.$this->file_name.self::EXT);
        return $this->manager->removeBy('id', $this->id) && $removed;
    }

    /**
     * Fonction retournant un objet par défaut
     * 
     * @see DbObject::default()
     */
    public static function default() {
        $image = new self([
            "id" => 0,
            "id_user" => 0,
            "file_name" => "nothing",
            "date_sent" => self::now(),
            "type" => self::TYPE_POST,
            "title" => "nothing"
        ]);
        return $image;
    }
}