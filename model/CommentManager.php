<?php

class CommentManager extends Manager {
    const COMMENT_PAGE = 2000;

    public function getComments(int $id_post, $page = 1) {
        $req = $this->_db->prepare('SELECT a.*, COUNT(b.id) AS replies_nbr FROM comments a LEFT JOIN comments b ON a.id = b.reply_to WHERE a.id_post=:id_post GROUP BY a.id ORDER BY a.date_publication ASC LIMIT '.(($page-1)*self::COMMENT_PAGE).','.$page*self::COMMENT_PAGE);
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
            throw new Exception("Aucun commentaire trouvé.");
        }
    } 

    public function getCommentById(int $id) {
        $req = $this->_db->prepare('SELECT a.*, COUNT(b.id) AS replies_nbr FROM comments a LEFT JOIN comments b ON a.id = b.reply_to WHERE a.id=:id');
        $req->bindParam(":id", $id);
        if($req->execute()) {
            $comment = new Comment($req->fetch());
            $req->closeCursor();
            return $comment;
        }
        else {
            throw new Exception("Commentaire $id non existant.");
        }
    }

    public function setComment(Comment $comment) {
        if ($comment->id == 0) {
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
        else {
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

    public function getReplies(Comment $comment) {
        $req = $this->_db->prepare('SELECT * FROM comments WHERE reply_to=?');
        if($req->execute([$comment->id])) {
            $comments = [];
            while($line = $req->fetch()) {
                $line['replies_nbr'] = 0;
                $comments[] = new Comment($line);
            }
            $req->closeCursor();
            return $comments;
        }
        else {
            throw new Exception("Commentaire $comment->id n'a aucune réponse.");
        }
    }
}