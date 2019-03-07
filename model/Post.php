<?php
namespace DartAlex;
/**
 * Classe représentant une ligne du tableau posts dans la bdd
 * 
 * @var int EXTRACT_LENGTH : La longueur par défaut des extraits de posts
 * @var int $id : Identifiant du post
 * @var string $date_publication : Date de publication du post au format DateTime
 * @var int $id_user : Identifiant de l'utilisateur auteur du post
 * @var string $title : Titre du post
 * @var string $content : Contenu du post
 * @var boolean $published : Est-ce que l'article est publié
 * @var int $comments_nbr : Nombre calculé de commentaires liés au post
 * @var User $user : Utilisateur associé au post
 * @var array $comments : Tous les commentaires associés à un post
 * @var PostManager $manager : PostManager
 * 
 * @see DbObject : classe parente
 */
class Post extends DbObject {

    const EXTRACT_LENGTH = 2000;
    
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
            case "date_publication":
                if(self::isDate($value)) {
                    $this->_attributes[$name] = (string) $value;
                }
                else {
                    throw new \Exception("Post: $name($value) n'est pas une date.");
                }
                break;
            case "id_user":
                $this->_attributes[$name] = (int) $value;
                break;
            case "title":
                if($value != "") {
                    $this->_attributes[$name] = (string) $value;
                }
                else {
                    throw new \Exception("Post: $name($value) est vide.");
                }
                break;
            case "content":
                if($value != "") {
                    $this->_attributes[$name] = (string) $value;
                }
                else {
                    throw new \Exception("Post: $name($value) est vide.");
                }
                break;
            case "published":
                $this->_attributes[$name] = (boolean) $value;
                break;
            case "comments_nbr":
                $this->_attributes[$name] = (int) $value;
                break;
            case "user":
                if(is_a($value, 'DartAlex\\User')) {
                    $this->_attributes["id_user"] = $value->id;
                    $this->_attributes[$name] = $value; 
                }
                else {
                    throw new \Exception("Post: $name(".var_export($value).") n'est pas un User.");
                }
                break;
            case "comments":
                if(is_array($value)) {
                    $this->_attributes[$name] = $value; 
                }
                else {
                    throw new \Exception("Post: $name(".var_export($value).") n'est pas un array.");
                }
                break;
            case "manager":
                if(is_a($value, 'DartAlex\\PostManager')) {
                    $this->_attributes[$name] = $value; 
                }
                else {
                    throw new \Exception("Post: $name(".var_export($value).") n'est pas un PostManager.");
                }
                break;
            default:
                throw new \Exception("Post: $name($value) attribut inconnu.");
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
                case "comments":
                    $commentManager = new CommentManager();
                    $this->$name = $commentManager->getComments($this->id, "all", true);
                    break;
                case "manager":
                    $this->$name = new PostManager();
            }
        }
        return parent::__get($name);
    }

    /**
     * Retourne un extrait du post de longueur définie par EXTRACT_LENGTH
     * 
     * @return string : Extrait du post
     */
    public function getExtract() {
        $extract = $this->content;

        if(strlen($extract) > self::EXTRACT_LENGTH) {
            $offset = strlen($this->content) - strlen(strip_tags($this->content));
            $length = self::EXTRACT_LENGTH + $offset;
            $last_space = strrpos(substr($this->content, $length, $length*2), '/(<\/.*>)|(<.*\/>)|( )/');
            $extract = substr($this->content, 0, $last_space+$length+1).'...';
        }
        return $extract;
    }

    /**
     * @see DbObject::save()
     */
    public function save() {
        return $this->manager->setPost($this);
    }

    /**
     * @see DbObject::delete()
     */
    public function delete() {
        if($this->comments_nbr > 0) {
            $reportManager = new ReportManager();
            $commentManager = new CommentManager();
            foreach($this->comments as $comment) {
                $reportManager->removeReportsByComment($comment);
            }
            $commentManager->removeCommentsByPost($this);
        }
        return $this->manager->removeBy('id', $this->id);
    }

    /**
     * Fonction retournant un objet par défaut
     * 
     * @see DbObject::default()
     */
    public static function default() {
        $post = new self([
            "id" => 0,
            "date_publication" => self::now(),
            "id_user" => 0,
            "title" => "nothing",
            "content" => "nothing",
            "published" => true,
            "comments_nbr" => 0
        ]);
        return $post;
    }

    /**
     * Retourne une chaine de caractères prête à être affichée en html
     * 
     * @param boolean : Le post fait-il partie d'une liste (false par défaut)
     * 
     * @return string : Commentaire prêt à être affiché
     */
    public function display($in_list = false) {
        $title = htmlspecialchars($this->title);
        $author = $this->user->displayName();
        $date = $this->rDate('date_publication');
        if($in_list) {
            $content = $this->getExtract();
            $display = "<div class='post box' id='post-$this->id'>";
            $display .= "<h3 class='post-title title is-4'>";
            $display .= "<a href='/post/$this->id/'>$title</a>";
            $display .= "<em> le $date par $author</em>";
            $display .= "</h3>";
            $display .= "<p class='content'>$content</p>";
            $display .= "<p><a href='/post/$this->id/'>";
            $display .= ($this->comments_nbr > 0)?"$this->comments_nbr commentaires.":"Aucun commentaire.";
            $display .= "</a></p>";
            $display .= "</div>";
        }
        else {
            $content = $this->content;
            $display = "<article class='post box'>";
            $display .= "<h2 class='post-title title is-3'>";
            $display .= $title;
            $display .= " <em>le $date par $author</em>";
            $display .= "</h2>";
            $display .= "<div class='post-content content'>";
            $display .= $content;
            $display .= "</div>";
            $display .= "</article>";
        }
        return $display;
    }

    /**
     * On veut savoir si l'utilisateur peut modifier le post
     * 
     * @param User $user : Utilisateur à tester
     * 
     * @return boolean : True si l'utilisateur peut éditer le post
     */
    public function canEdit(User $user) {
        if($this->id_user == $user->id) {
            return true;
        }
        if($user->level >= User::LEVEL_ADMIN) {
            return true;
        }
        return false;
    }

    /**
     * On veut savoir si le post est publié
     * 
     * @return boolean : True si le post est publié
     */
    public function isPublished() {
        $date = new \DateTime($this->date_publication);
        $now = new \DateTime("now");
        return ($date <= $now) && $this->published;
    }

}