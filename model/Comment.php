<?php
/**
 * Classe représentant une ligne du tableau comments de la bdd
 * 
 * @var int $id : Identifiant du commentaire
 * @var int $id_post : Identifiant du post auquel le commentaire est lié
 * @var int $id_user : Identifiant de l'utilisateur qui a envoyé le commentaire (0 si anonyme)
 * @var int $reply_to : Identifiant du commentaire auquel ce commentaire est lié (0 si aucun)
 * @var string $date_publication : Date de publication au format DateTime
 * @var string $ip : Ip qui a posté ce commentaire
 * @var string $name : Nom de l'auteur du commentaire
 * @var string $content : Corps du commentaire
 * @var int $replies_nbr : Nombre calculé de réponses à ce commentaire
 * 
 * @see DbObject : classe parente
 */
class Comment extends DbObject {

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
                    throw new Exception("Comment: $name($value) n'est pas une date.");
                }
                break;
            case "ip":
                if(self::isIp($value)) {
                    $this->_attributes[$name] = (string) $value;
                }
                else {
                    throw new Exception("Comment: $name($value) n'est pas une ip.");
                }
                break;
            case "name":
                if($value != "") {
                    $this->_attributes[$name] = (string) $value;
                }
                else {
                    throw new Exception("Comment: $name($value) est vide.");
                }
                break;
            case "content":
                if($value != "") {
                    $this->_attributes[$name] = (string) $value;
                }
                else {
                    throw new Exception("Comment: $name($value) est vide.");
                }
                break;
            case "replies_nbr":
                $this->_attributes[$name] = (int) $value;
                break;
            default:
                throw new Exception("Comment: $name($value) attribut inconnu.");
                break;
        }
    }

    /**
     * Fonction retournant un objet par défaut
     * 
     * @see DbObject::default()
     */
    public static function default() {
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
        ]);
        return $comment;
    }

    /**
     * Fonction retournant le nom à afficher pour un commentaire.
     * L'attribut name du commentaire si l'utilisateur était anonyme, sinon le name_display de l'utilisateur.
     * 
     * @return string : Nom de l'auteur du commentaire.
     */
    public function getName() {
        if($this->id_user == 0) {
            return $this->name;
        }
        else { // Si l'auteur n'est pas anonyme, on récupère son user pour retourner son nom
            $userManager = new UserManager();
            $user = $userManager->getUserById($this->id_user);
            return $user->name_display;
        }
    }
    
    /**
     * Fonction déterminant si un utilisateur peut éditer un commentaire.
     * @param User $user : L'utilisateur à tester
     * 
     * @return boolean : True si l'utilisateur peut éditer le commentaire
     */
    public function canEdit(User $user) {
        if($user->id == 0) {
            if($user->ip == $this->ip) {
                return true;
            }
        }
        else {
            if($user->id == $this->id_user) {
                return true;
            }
        }
        return false;
    }

}