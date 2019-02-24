<?php
/**
 * Classe gérant les interactions avec la bdd en rapport avec la table posts
 * 
 * @var int POST_PAGE : Constante définissant le nombre de posts à afficher par page
 * 
 * @see Manager : classe parente
 */
class PostManager extends Manager {

    const POST_PAGE = 5;

    /**
     * Retourne la liste des posts selon différents critères.
     * Par page (par défaut 1), si ils sont publiés ou non (par défaut true), par année, année et mois, année et mois et jour.
     * 
     * @param mixed $page : Numéro de la page à récupérer (défaut = 1) "all" si on veut récupérer tous les posts
     * @param boolean $published : false si on récupère aussi les articles non publiés (défaut = true)
     * @param int $year : Année des articles à récupérer (défaut = 0, non pris en compte)
     * @param int $month : Mois des articles à récupérer (défaut = 0, non pris en compte)
     * @param int $day : Jour des articles à récupérer (défaut = 0, non pris en compte)
     * 
     * @return array : Tableau d'objets Post triés par date de publication répondant aux critères
     */
    public function getPosts($page = 1, $published = true, $year = 0, $month = 0, $day = 0) {
        $query_start = 'SELECT posts.*, COUNT(comments.id) AS comments_nbr FROM posts LEFT JOIN comments ON posts.id = comments.id_post ';
        if(is_int($page)) {
            $query_end =  ' GROUP BY posts.id ORDER BY posts.date_publication DESC LIMIT '.(($page-1)*self::POST_PAGE).','.$page*self::POST_PAGE;
        }
        elseif($page == "all") {
            $query_end =  ' GROUP BY posts.id ORDER BY posts.date_publication DESC';
        }
        else {
            throw new Exception("PostManager: \$page($page) invalide.");
            return [];
        }
        if($year != 0) {// Si $year est renseigné
            if($month != 0) {// Si $month est renseigné
                if($day != 0) {// Si $day est renseigné, on recherche par année, mois et jour
                    $req = $this->_db->prepare($query_start.'WHERE'.($published?' posts.published = 1 AND posts.date_publication<=NOW() AND':'').' YEAR(posts.date_publication) = :year AND MONTH(posts.date_publication) = :month AND DAY(posts.date_publication) = :day'.$query_end);
                    $req->bindParam(":year",(int) $year);
                    $req->bindParam(":month",(int) $month);
                    $req->bindParam(":day",(int) $day);
                }
                else {// Si $day n'est pas renseigné, on recherche par année et mois
                    $req = $this->_db->prepare($query_start.'WHERE'.($published?' posts.published = 1 AND posts.date_publication<=NOW() AND':'').' YEAR(posts.date_publication) = :year AND MONTH(posts.date_publication) = :month'.$query_end);
                    $req->bindParam(":year",(int) $year);
                    $req->bindParam(":month",(int) $month);
                }
            }
            else {// Si $month n'est pas renseigné, on recherche par année
                $req = $this->_db->prepare($query_start.'WHERE'.($published?' posts.published = 1 AND posts.date_publication<=NOW() AND':'').' YEAR(posts.date_publication) = :year'.$query_end);
                $req->bindParam(":year",(int) $year);
            }
        }
        else {// Si $year n'est pas renseigné, on recherche les derniers posts publiés
            $req = $this->_db->prepare($query_start.($published?'WHERE posts.published = 1 AND posts.date_publication<=NOW()':'').$query_end);
        }
        if($req->execute()) {
            $posts = [];
            while($line = $req->fetch()) {
                $post = new Post($line);
                $posts[] = $post;
            }
            $req->closeCursor();
            return $posts;
        }
        else {
            throw new Exception("PostManager: Aucun post trouvé.");
            return [];
        }
    }

    /**
     * Retourne le post désigné par une id
     * 
     * @param int $id : Identifiant du post qu'on veut récupérer
     * 
     * @return Post : Le post à retourner
     */
    public function getPostById(int $id) {
        $req = $this->_db->prepare('SELECT posts.*, COUNT(comments.id) AS comments_nbr FROM posts LEFT JOIN comments ON posts.id = comments.id_post WHERE posts.id=?');
        if($req->execute([$id])) {
            $post = new Post($req->fetch());
            $req->closeCursor();
            return $post;
        }
        else {
            throw new Exception("PostManager: Aucun post correspondant à l'id $id.");
        }
    }

    /**
     * Enregistre un nouveau post, ou en modifie un existant dans la bdd
     * Enregistre si l'id est à 0, sinon modifie.
     * 
     * @param Post $post : Le post à mettre à jour, ou enregistrer
     * 
     * @return boolean : true si la requête a été executée avec succès, false sinon.
     */
    public function setPost(Post $post) {
        if ($post->id == 0) {
            $req = $this->_db->prepare('INSERT INTO posts(date_publication, id_user, title, content, published) VALUES (?, ?, ?, ?, ?)');
            $exec = $req->execute([
                $post->date_publication,
                $post->id_user,
                $post->title,
                $post->content,
                $post->published
            ]);
            
        }
        else {
            $req = $this->_db->prepare('UPDATE posts SET date_publication=?, id_user=?, title=?, content=?, published=? WHERE id=?');
            $exec = $req->execute([
                $post->date_publication,
                $post->id_user,
                $post->title,
                $post->content,
                $post->published,
                $post->id
            ]);
        }
        $req->closeCursor();
        return $exec;
    }

    /**
     * Compte le nombre total de posts
     * 
     * @param boolean $published : Si on ne doit compter que les posts publiés (true par défaut)
     * @param int $year : Si on doit chercher par année (0 par défaut, on ne cherche pas)
     * @param int $month : Si on doit chercher par mois (0 par défaut, on ne cherche pas)
     * @param int $day : Si on doit chercher par jour (0 par défaut, on ne cherche pas)
     * 
     * @return int : Nombre de posts
     */
    public function count($published = true, $year = 0, $month = 0, $day = 0) {
        $query_start = 'SELECT COUNT(*) as count FROM posts ';
        if($year != 0) {// Si $year est renseigné
            if($month != 0) {// Si $month est renseigné
                if($day != 0) {// Si $day est renseigné, on recherche par année, mois et jour
                    $req = $this->_db->prepare($query_start.'WHERE'.($published?' published = 1 AND date_publication<=NOW() AND':'').' YEAR(date_publication) = :year AND MONTH(date_publication) = :month AND DAY(date_publication) = :day');
                    $req->bindParam(":year",(int) $year);
                    $req->bindParam(":month",(int) $month);
                    $req->bindParam(":day",(int) $day);
                }
                else {// Si $day n'est pas renseigné, on recherche par année et mois
                    $req = $this->_db->prepare($query_start.'WHERE'.($published?' published = 1 AND date_publication<=NOW() AND':'').' YEAR(date_publication) = :year AND MONTH(date_publication) = :month');
                    $req->bindParam(":year",(int) $year);
                    $req->bindParam(":month",(int) $month);
                }
            }
            else {// Si $month n'est pas renseigné, on recherche par année
                $req = $this->_db->prepare($query_start.'WHERE'.($published?' published = 1 AND date_publication<=NOW() AND':'').' YEAR(date_publication) = :year');
                $req->bindParam(":year",(int) $year);
            }
        }
        else {// Si $year n'est pas renseigné, on recherche les derniers posts publiés
            $req = $this->_db->prepare($query_start.($published?'WHERE published = 1 AND date_publication<=NOW()':''));
        }
        if($req->execute()) {
            $res = $req->fetch();
            $req->closeCursor();
            return (int) $res['count'];
        }
        else {
            throw new Exception("PostManager: Aucun post trouvé.");
            return 0;
        }
    }
}