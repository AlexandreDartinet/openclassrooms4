<?php
/**
 * Classe gérant les interactions avec la bdd en rapport avec la table comments
 * 
 * @var int COMMENT_PAGE : Constante définissant le nombre de commentaires à afficher par page
 * 
 * @see Manager : classe parente
 */
class CommentManager extends Manager {

    const COMMENT_PAGE = 20;

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
            throw new Exception("CommentManager: Aucun commentaire trouvé \$id_post($id_post), \$page($page), \$replies($replies).");
            return [];
        }
    } 

    /**
     * Récupère un commentaire par son identifiant
     * 
     * @param int $id : identifiant du commentaire à récupérer
     * 
     * @return Comment : L'objet Comment correspondant au commentaire demandé
     */
    public function getCommentById(int $id) {
        $req = $this->_db->prepare('SELECT a.*, COUNT(b.id) AS replies_nbr FROM comments a LEFT JOIN comments b ON a.id = b.reply_to WHERE a.id=:id');
        $req->bindParam(":id", $id);
        if($req->execute()) {
            $comment = new Comment($req->fetch());
            $req->closeCursor();
            return $comment;
        }
        else {
            throw new Exception("CommentManager: Commentaire $id non existant.");
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
            throw new Exception("CommentManager: Commentaire $comment->id n'a aucune réponse.");
        }
    }

    /**
     * Compte le nombre de commentaires d'un post
     * 
     * @param int $id_post : Identifiant du post
     * @param boolean $replies : Si on doit compter les réponses (par défaut false)
     * 
     * @return int : Nombre de commentaires
     */
    public function count(int $id_post, $replies = false) {
        $req = $this->_db->prepare('SELECT COUNT(*) as count FROM comments WHERE id_post=:id_post'.(($replies)?'':' AND reply_to=0'));
        $req->bindParam(':id_post', $id_post);
        if($req->execute()) {
            $res = $req->fetch();
            $req->closeCursor();
            return (int) $res['count'];
        }
        else {
            throw new Exception("CommentManager: Post $id_post n'a aucun commentaire.");
            return 0;
        }
    }
}