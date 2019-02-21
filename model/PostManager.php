<?php
class PostManager extends Manager {

    const POST_PAGE = 5;

    public function getPosts($page = 1, $published = true, $year = 0, $month = 0, $day = 0) {
        if($year != 0) {
            if($month != 0) {
                if($day != 0) {
                    $req = $this->_db->prepare('SELECT * FROM posts WHERE'.($published?' published = 1 AND':'').' YEAR(date_publication) = :year AND MONTH(date_publication) = :month AND DAY(date_publication) = :day ORDER BY date_publication DESC LIMIT '.(($page-1)*self::POST_PAGE).','.$page*self::POST_PAGE);
                    $req->bindParam(":year",$year);
                    $req->bindParam(":month",$month);
                    $req->bindParam(":day",$day);
                }
                else {
                    $req = $this->_db->prepare('SELECT * FROM posts WHERE'.($published?' published = 1 AND':'').' YEAR(date_publication) = :year AND MONTH(date_publication) = :month ORDER BY date_publication DESC LIMIT '.(($page-1)*self::POST_PAGE).','.$page*self::POST_PAGE);
                    $req->bindParam(":year",$year);
                    $req->bindParam(":month",$month);
                }
            }
            else {
                $req = $this->_db->prepare('SELECT * FROM posts WHERE'.($published?' published = 1 AND':'').' YEAR(date_publication) = :year ORDER BY date_publication DESC LIMIT '.(($page-1)*self::POST_PAGE).','.$page*self::POST_PAGE);
                $req->bindParam(":year",$year);
            }
        }
        else {
            $req = $this->_db->prepare('SELECT * FROM posts'.($published?' WHERE published = 1':'').' ORDER BY date_publication DESC LIMIT '.(($page-1)*self::POST_PAGE).','.$page*self::POST_PAGE);
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
            throw new Exception("Aucun post trouvé.");
        }
    }

    public function getPostById(int $id) {
        $req = $this->_db->prepare('SELECT * FROM posts WHERE id=?');
        if($req->execute([$id])) {
            $post = new Post($req->fetch());
            $req->closeCursor();
            return $post;
        }
        else {
            throw new Exception("Aucun post correspondant à l'id $id.");
        }
    }

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


}