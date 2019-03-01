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
 * @var int $reports_nbr : Nombre de signalements à ce commentaire
 * @var User $user : Utilisateur associé au commentaire
 * @var Post $post : Post associé au commentaire
 * @var array $replies : Tableau de commentaires qui sont des réponses à ce commentaire
 * @var array $reports : Tableau des signalement de ce commentaire
 * @var Comment $parent : Commentaire duquel celui ci est une réponse, default si pas une réponse
 * @var CommentManager $manager : CommentManager
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
            case "reports_nbr":
                $this->_attributes[$name] = (int) $value;
                break;
            case "user":
                if(is_a($value, 'User')) {
                    $this->_attributes["id_user"] = $value->id;
                    $this->_attributes[$name] = $value;
                }
                else {
                    throw new Exception("Comment: $name(".var_export($value).") n'est pas un User.");
                }
                break;
            case "post":
                if(is_a($value, 'Post')) {
                    $this->_attributes["id_post"] = $value->id;
                    $this->_attributes[$name] = $value;
                }
                else {
                    throw new Exception("Comment: $name(".var_export($value).") n'est pas un Post.");
                }
                break;
            case "replies":
                if(is_array($value)) {
                    $this->_attributes[$name] = $value;
                }
                else {
                    throw new Exception("Comment: $name(".var_export($value).") n'est pas un Array.");
                }
                break;
            case "reports":
                if(is_array($value)) {
                    $this->_attributes[$name] = $value;
                }
                else {
                    throw new Exception("Comment: $name(".var_export($value).") n'est pas un Array.");
                }
                break;
            case "parent":
                if(is_a($value, 'Comment')) {
                    $this->_attributes[$name] = $value;
                }
                else {
                    throw new Exception("Comment: $name(".var_export($value).") n'est pas un Comment.");
                }
                break;
            case "manager":
                if(is_a($value, 'CommentManager')) {
                    $this->_attributes[$name] = $value;
                }
                else {
                    throw new Exception("Comment: $name(".var_export($value).") n'est pas un CommentManager.");
                }
                break;
            default:
                throw new Exception("Comment: $name($value) attribut inconnu.");
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
                    $this->$name = $user;
                    break;
                case "post":
                    $postManager = new PostManager();
                    $post = $postManager->getPostById($this->id_post);
                    $this->$name = $post;
                    break;
                case "replies":
                    $replies = $this->manager->getReplies($this);
                    $this->$name = $replies;
                    break;
                case "reports":
                    $reportManager = new ReportManager();
                    $reports = $reportManager->getReports($this->id, "all");
                    $this->$name = $reports;
                    break;
                case "reports_nbr":
                    if(isset($this->reports)) {
                        $this->$name = sizeof($this->reports);
                    }
                    else {
                        $reportManager = new ReportManager();
                        $this->$name = $reportManager->count('id_comment', $this->id);
                    }
                    break;
                case "parent":
                    if($this->reply_to != 0) {
                        $parent = $this->manager->getCommentById($this->reply_to);
                    }
                    else {
                        $parent = false;
                    }
                    $this->$name = $parent;
                    break;
                case "manager":
                    $this->$name = new CommentManager();
                    break;
            }
        }
        return parent::__get($name);
    }

    /**
     * @see DbObject::save()
     */
    public function save() {
        return $this->manager->setComment($this);
    }

    /**
     * @param boolean $force : true si on veut forcer la suppression
     * 
     * @see DbObject::delete()
     */
    public function delete($force = false) {
        $remove = $force; // Par défaut on ne force pas la suppression
        $reportManager = new ReportManager();
        $reportManager->removeBy('id_comment', $this->id); // On supprime les reports en rapport avec le commentaire
        if($force and ($this->replies_nbr > 0)) { // Si on force la suppression, et qu'il y a des réponses, on supprime également les reports des réponses
            foreach($this->replies as &$reply) {
                $reportManager->removeBy('id_comment', $reply->id);
            }
        }
        if($this->reply_to == 0) { // Si le commentaire n'est pas une réponse
            if($this->replies_nbr == 0) { // Si le commentaire n'a aucune réponse, on le supprime
                $remove = true;
            }
        }
        else { // Si le commentaire est une réponse, on peut toujours le supprimer
            $remove = true;
            $parentComment = $this->parent;
            /**
             * Si le commentaire parent n'a plus de réponses après la suppression, et a été supprimé, on le supprime définitivement
             */
            if($parentComment->replies_nbr <= 1 && $parentComment->ip == "0.0.0.0" && $parentComment->id_user == 0) {
                return $parentComment->delete(true); // On peut sortir de la fonction, cet appel va supprimer aussi le commentaire actuel
            }
        }
        if($remove) {
            return $this->manager->removeComment($this);
        }
        else {
            $this->content = '<Supprimé>';
            $this->id_user = 0;
            $this->name = 'Supprimé';
            $this->ip = '0.0.0.0';
            return $this->save();
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
            return $this->user->name_display;
        }
    }

    /**
     * Fonction retournant le lien vers le profil de l'auteur du commentaire, si le profil existe
     * 
     * @return string : Lien vers le profil
     */
    public function displayName() {
        if($this->id_user == 0) {
            return htmlspecialchars($this->name);
        }
        else {
            return $this->user->displayName();
        }
    }
    
    /**
     * Fonction déterminant si un utilisateur peut éditer un commentaire.
     * @param User $user : L'utilisateur à tester
     * 
     * @return boolean : True si l'utilisateur peut éditer le commentaire
     */
    public function canEdit(User $user) {
        if(!$user->canComment()) {
            return false;
        }
        if($user->id == 0) {
            if(($user->ip == $this->ip) && (0 == $this->id_user)) {
                return true;
            }
        }
        else {
            if($user->id == $this->id_user) {
                return true;
            }
            if($user->level >= User::LEVEL_MODERATOR) {
                return true;
            }
        }
        return false;
    }
    /**
     * Retourne une chaine de caractères prête à être affichée en html
     * 
     * @param boolean $display_buttons : Doit-on afficher les boutons (par défaut false)
     * @param boolean $highlight : Doit-on mettre le commentaire en avant (par défaut false)
     * @param boolean $append_id : Doit-on ajouter l'id à la div du commentaire
     * @param boolean $noReply : Doit-on cacher le bouton répondre
     * 
     * @return string : Commentaire prêt à être affiché
     */
    public function display($display_buttons = true, $highlight = false, $append_id = true, $noReply = false) {
        $class = 'comment'.((($this->reply_to != 0) && $append_id)?" comment-reply comment-reply-to-$this->reply_to":'').(($highlight)?' comment-highlight':'');
        $id = ($append_id || $noReply)?" id='comment-$this->id'":"";
        $display = "<div class='$class'$id>";
        $author = $this->displayName();
        $date = $this->rDate('date_publication');
        $display .= "<p>";
        $display .= "<strong>$author</strong> le $date ";
        if($_SESSION['user']->level >= User::LEVEL_ADMIN) {
            $display .= " IP(".$this->user->ip.") ";
        }
        if($display_buttons && $_SESSION['user']->canComment()) {
            $display .= "<div class='comment-buttons'>";
            if($this->reply_to == 0 && !$noReply) {
                $display .= " <a title='Répondre' class='fas fa-reply comment-reply-link' id='comment-reply-link-$this->id' href='".PATH."reply_to/$this->id/'></a> ";
            }
            if($this->canEdit($_SESSION['user'])) {
                $display .= " <a title='Éditer' class='fas fa-edit comment-edit-link' id='comment-edit-link-$this->id' href='".PATH."edit/$this->id/'></a> ";
                $display .= " <a title='Supprimer' class='fas fa-trash comment-delete-link' id='comment-delete-link-$this->id' href='".PATH."delete/$this->id/'></a> ";
            }
            $display .= " <a title='Signaler' class='fas fa-flag comment-report-link' id='comment-report-link-$this->id' href='".PATH."report/$this->id/'></a> ";
            if($_SESSION['user']->level >= User::LEVEL_MODERATOR && $this->reports_nbr > 0) {
                $display .= " <a title='Signalements($this->reports_nbr)' class='fas fa-exclamation-triangle comment-reports-link' id='comment-reports-link-$this->id' href='/admin/reports/comment/$this->id/'>($this->reports_nbr)</a> ";
            }
            $display .= "</div>";
        }
        $display .= "</p><p>";
        $display .= nl2br(htmlspecialchars($this->content));
        $display .= "</p></div>";
        return $display;
    }

}