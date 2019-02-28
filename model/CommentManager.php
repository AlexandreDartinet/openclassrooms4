<?php
/**
 * Classe gérant les interactions avec la bdd en rapport avec la table comments
 * 
 * @var int COMMENT_PAGE : Constante définissant le nombre de commentaires à afficher par page
 * @var string TABLE_NAME : Nom de la table
 * 
 * @see Manager : classe parente
 */
class CommentManager extends Manager {

    const COMMENT_PAGE = 20;
    const TABLE_NAME = 'comments';

    /**
     * Retourne tous les commentaires liés à un post, par page (par défaut page 1)
     * 
     * @param int $id_post : L'identifiant du post dont on veut les commentaires
     * @param mixed $page : Optionnel, numéro de page de commentaires (par défaut 1) ou "all" si on veut tous les commentaires
     * @param boolean $replies : Si on veut récupérer les réponses en plus des commentaires
     * 
     * @return array : Tableau d'objets Comment représentant les commentaires du post, triés par date d'envoi.
     */
    public function getComments(int $id_post, $page = 1, $replies = false) {
        if(is_int($page)) {
            $req = $this->_db->prepare('SELECT a.*, COUNT(b.id) AS replies_nbr FROM comments a LEFT JOIN comments b ON a.id = b.reply_to WHERE a.id_post=:id_post'.(($replies)?'':' AND a.reply_to=0').' GROUP BY a.id ORDER BY a.date_publication ASC LIMIT '.(($page-1)*self::COMMENT_PAGE).','.$page*self::COMMENT_PAGE);
        }
        elseif($page == "all") {
            $req = $this->_db->prepare('SELECT a.*, COUNT(b.id) AS replies_nbr FROM comments a LEFT JOIN comments b ON a.id = b.reply_to WHERE a.id_post=:id_post'.(($replies)?'':' AND a.reply_to=0').' GROUP BY a.id ORDER BY a.date_publication ASC');
        }
        else {
            throw new Exception("CommentManager: Paramètre \$page($page) invalide.");
            return [];
        }
        $req->bindParam(":id_post", $id_post);
        if($req->execute()) {
            $comments = [];
            while($line = $req->fetch()) {
                $comment = new Comment($line);
                $comments[] = $comment;
            }
            $req->closeCursor();
            return $comments;
        }
        else {
            throw new Exception("CommentManager: Erreur de requête \$id_post($id_post), \$page($page), \$replies($replies).");
        }
    }
    
    /**
     * Retourne tous les commentaires liés à un post, après un certain id
     * 
     * @param int $id_post : L'identifiant du post dont on veut les commentaires
     * @param int $last_id : L'identifiant du commentaire après lequel on veut des mises à jour
     * 
     * @return array : Tableau d'objets Comment représentant les commentaires du post, triés par date d'envoi.
     */
    public function getCommentsAfter(int $id_post, int $last_id) {
        $req = $this->_db->prepare('SELECT a.*, COUNT(b.id) AS replies_nbr FROM comments a LEFT JOIN comments b ON a.id = b.reply_to WHERE a.id_post=:id_post AND a.id>:last_id GROUP BY a.id ORDER BY a.date_publication ASC');
        $req->bindParam(":id_post", $id_post);
        $req->bindParam(':last_id', $last_id);
        if($req->execute()) {
            $comments = [];
            while($line = $req->fetch()) {
                $comment = new Comment($line);
                $comments[] = $comment;
            }
            $req->closeCursor();
            return $comments;
        }
        else {
            throw new Exception("CommentManager: Erreur de requête \$id_post($id_post), \$page($page), \$replies($replies).");
        }
    }

    /**
     * Récupère un commentaire par son identifiant
     * 
     * @param int $id : identifiant du commentaire à récupérer
     * 
     * @return Comment : L'objet Comment correspondant au commentaire demandé, false si aucun résultat
     */
    public function getCommentById(int $id) {
        $req = $this->_db->prepare('SELECT a.*, COUNT(b.id) AS replies_nbr FROM comments a LEFT JOIN comments b ON a.id = b.reply_to WHERE a.id=:id');
        $req->bindParam(":id", $id);
        if($req->execute()) {
            if($res = $req->fetch()) {
                $comment = new Comment($res);
            }
            else {
                $comment = false;
            }
            $req->closeCursor();
            return $comment;
        }
        else {
            throw new Exception("CommentManager: Erreur de requête getCommentById($id).");
        }
    }

    /**
     * Récupère les commentaires d'un utilisateur
     * 
     * @param User $user : L'user dont on veut récupérer les commentaires
     * 
     * @return array : Un tableau d'objets Comment
     */
    public function getCommentsByUser(User $user) {
        $req = $this->_db->prepare('SELECT a.*, COUNT(b.id) AS replies_nbr FROM comments a LEFT JOIN comments b ON a.id = b.reply_to WHERE a.id_user=?');
        if($req->execute([$user->id])) {
            $comments = [];
            while($line = $req->fetch()) {
                $comments[] = new Comment($line);
            }
            $req->closeCursor();
            return $comments;
        }
        else {
            throw new Exception("CommentManager: Erreur de requête getCommentsByUser($user->id).");
        }
    }

    /**
     * Enregistre un nouveau commentaire, ou en modifie un existant dans la bdd
     * Enregistre si l'id est à 0, sinon modifie.
     * 
     * @param Comment $comment : Le commentaire à mettre à jour, ou enregistrer
     * 
     * @return boolean : true si la requête a été executée avec succès, false sinon.
     */
    public function setComment(Comment $comment) {
        if ($comment->id == 0) { // Si c'est un nouveau commentaire
            $req = $this->_db->prepare('INSERT INTO comments(id_post, id_user, reply_to, ip, name, content) VALUES (?, ?, ?, ?, ?, ?)');
            $exec = $req->execute([
                $comment->id_post,
                $comment->id_user,
                $comment->reply_to,
                $comment->ip,
                $comment->name,
                $comment->content
            ]);
        }
        else { // Si le commentaire existe déjà
            $req = $this->_db->prepare('UPDATE comments SET id_post=?, id_user=?, reply_to=?, date_publication=?, ip=?, name=?, content=? WHERE id=?');
            $exec = $req->execute([
                $comment->id_post,
                $comment->id_user,
                $comment->reply_to,
                $comment->date_publication,
                $comment->ip,
                $comment->name,
                $comment->content,
                $comment->id
            ]);
        }
        $req->closeCursor();
        return $exec;
    }

    /**
     * Récupère les réponses à un commentaire
     * 
     * @param Comment $comment : Le commentaire dont on veut récupérer les réponses
     * 
     * @return array : Un tableau d'objets Comment qui sont des réponses au commentaire passé en argument
     */
    public function getReplies(Comment $comment) {
        $req = $this->_db->prepare('SELECT * FROM comments WHERE reply_to=? ORDER BY date_publication ASC');
        if($req->execute([$comment->id])) {
            $comments = [];
            while($line = $req->fetch()) {
                $line['replies_nbr'] = 0; // Si le commentaire est une réponse, il ne peut pas avoir de réponses
                $comments[] = new Comment($line);
            }
            $req->closeCursor();
            return $comments;
        }
        else {
            throw new Exception("CommentManager: Erreur de requête getReplies($comment->id).");
        }
    }

    /**
     * Fonction pour supprimer un commentaire de la bdd et ses réponses
     * 
     * @param Comment $comment : Commentaire à supprimer
     * 
     * @return boolean : true si execution réussie
     */
    public function removeComment(Comment $comment) {
        $req = $this->_db->prepare('DELETE FROM comments WHERE id=:id OR reply_to=:id');
        $id = $comment->id;
        $req->bindParam(':id', $id);
        return $req->execute();
    }
    
    /**
     * Fonction pour supprimer tous les commentaires associés à un post
     * 
     * @param Post $post : Post dont on veut supprimer les commentaires
     * 
     * @return boolean : true si execution réussie
     */
    public function removeCommentsByPost(Post $post) {
        return $this->removeBy('id_post', $post->id);
    }

    /**
     * Compte le nombre de commentaires d'un post
     * 
     * @param int $id_post : Identifiant du post
     * @param boolean $replies : Si on doit compter les réponses (par défaut false)
     * 
     * @return int : Nombre de commentaires
     */
    public function countByPostId(int $id_post, $replies = false) {
        $req = $this->_db->prepare('SELECT COUNT(*) as count FROM comments WHERE id_post=:id_post'.(($replies)?'':' AND reply_to=0'));
        $req->bindParam(':id_post', $id_post);
        if($req->execute()) {
            if($res = $req->fetch()) {
                $count = (int) $res['count'];
            }
            else {
                $count = 0;
            }
            $req->closeCursor();
            return $count;
        }
        else {
            throw new Exception("CommentManager: Erreur de requête countByPostId($id_post, $replies).");
        }
    }

    /**
     * Retourne le nombre de commentaires créés par un utilisateur.
     * 
     * @param User $user : Utilisateur dont on veut récupérer le nombre de commentaires
     * 
     * @return int : Nombre de commentaires
     */
    public function countCommentsByUser(User $user) {
        return $this->count('id_user', $user->id);
    }

    /**
     * Retire un utilisateur des commentaires et le change en anonyme.
     * 
     * @param User $user : Utilisateur qu'on souhaite retirer
     * 
     * @return boolean : true si succès
     */
    public function removeUser(User $user) {
        $req = $this->_db->prepare('UPDATE comments SET id_user=0 WHERE id_user=?');
        return $req->execute([$user->id]);
    }
}