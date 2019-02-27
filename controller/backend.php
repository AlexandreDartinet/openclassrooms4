<?php
/**
 * Toutes les fonctions relatives à l'affichage et au traitement des données de la partie backend du site
 */

/**
 * Fonctions relatives à l'affichage
 */

/**
 * Affiche la page d'accueil de l'interface d'administration
 * 
 * @return void
 */
function viewAdmin() {
    $title = "Accueil";

    require('view/backend/adminView.php');
}

/**
 * Affiche la liste des commentaires signalés
 * 
 * @param int $page : Page à afficher
 * 
 * @return void
 */
function listReports(int $page) {
    $reportManager = new ReportManager();
    $id_comments = $reportManager->getCommentsId($page);
    if(sizeof($id_comments) > 0) {
        $comments = [];
        $commentManager = new CommentManager();
        foreach($id_comments as $id_comment => $reports_nbr) {
            $comments[] = $commentManager->getCommentById((int) $id_comment); 
        }
        $title = 'Signalements';
        $pageSelector = pageSelector(ceil($reportManager->countComments()/CommentManager::COMMENT_PAGE), $page, PATH);

        require('view/backend/listReportsView.php');
    }
    else {
        header('Location: /admin/retry/no_reports/');
    }
}

/**
 * Affiche la liste des signalements liés à un commentaire
 * 
 * @param int $id : Identifiant du commentaire
 * @param int $page : Page à afficher
 * 
 * @return void
 */
function viewCommentReports(int $id, int $page) {
    $commentManager = new CommentManager();
    if($comment = $commentManager->getCommentById($id)) {
        $reportManager = new ReportManager();
        if($reportManager->exists('id_comment', $id)) {
            $reports = $reportManager->getReports($id,$page);
            $title = "Signalements - Commentaire $id";
            $pageSelector = pageSelector(ceil($reportManager->count('id_comment', $id)/ReportManager::REPORT_PAGE), $page, PATH);

            require('view/backend/commentReportsView.php');
        }
        else {
            header('Location: /admin/reports/retry/no_comment_report/');
        }
    }
    else {
        header('Location: /admin/reports/retry/unknown_id_comment/');
    }
}

/**
 * Affiche la liste des posts dans l'interface d'administration
 * 
 * @param int $page : Page à afficher
 * 
 * @return void
 */
function listPosts(int $page) {
    $postManager = new PostManager();
    $posts = $postManager->getPosts($page, false);
    $title = 'Articles';
    $pageSelector = pageSelector(ceil($postManager->countPosts(false)/PostManager::POST_PAGE), $page, PATH);

    require('view/backend/listPostsView.php');
}

/**
 * Affiche le formulaire d'édition d'un article
 * 
 * @param int $id : Id de l'article à modifier, 0 pour un nouvel article
 * 
 * @return void
 */
function viewPost(int $id) {
    if($id == 0) {
        $new = true;
        $title = "Nouvel article";
    }
    else {
        $new = false;
        $postManager = new PostManager();
        if($post = $postManager->getPostById($id)) {
            if($post->canEdit($_SESSION['user'])) {
                $title = 'Article "'.htmlspecialchars($post->title).'"';
            }
            else {
                header('Location: /admin/posts/retry/no_access/');
                return;
            }
        }
        else {
            header('Location: /admin/posts/retry/id_post/');
            return;
        }
    }

    require('view/backend/postView.php');
}

/**
 * Fonctions relatives au traitement des données
 */

/**
 * Supprime un signalement
 * Renvoie à la page du commentaire dont le signalement est issu
 * 
 * @param int $id : Identifiant du signalement
 * 
 * @return void
 */
function deleteReport(int $id) {
    $reportManager = new ReportManager();
    $path = preg_replace('/delete_report\/\d+\//', '', PATH);
    if($report = $reportManager->getReportById($id)) {
        $report->delete();
        header('Location: '.$path.'success/report_delete/');
    }
    else {
        header('Location: '.$path.'retry/invalid_id_report/');
    }
}

/**
 * Supprime un commentaire
 * Renvoie à la page des signalements
 * 
 * @param int $id : Identifiant du commentaire
 * 
 * @return void
 */
function deleteComment(int $id) {
    $commentManager = new CommentManager();
    $path = preg_replace('/delete\/\d+\//', '', PATH);
    if($comment = $commentManager->getCommentById($id)) {
        $comment->delete();
        header('Location: /admin/reports/success/deleted_comment/');
    }
    else {
        header('Location: /admin/reports/retry/unknown_id_comment/');
    }
}

/**
 * Change l'attribut published d'un post
 * 
 * @param int $id : Identifiant du post
 * @param boolean $published : attribut published
 * 
 * @return void
 */
function publishPost(int $id, $published) {
    if($published && $_SESSION['user']->level < User::LEVEL_ADMIN) {
        header("Location: /admin/posts/retry/no_access/1");
        return;
    }
    $postManager = new PostManager();
    if($post = $postManager->getPostById($id)) {
        if($post->canEdit($_SESSION['user'])) {
            $post->published = $published;
            $post->save();
            header("Location: /admin/posts/success/post_".(($published)?"":"un")."published/");
        }
        else {
            header("Location: /admin/posts/retry/no_access/2");
        }
    }
    else {
        header("Location: /admin/posts/retry/id_post/");
    }
}

/**
 * Supprime un article
 * 
 * @param int $id : Identifiant de l'article
 * 
 * @return void
 */
function deletePost(int $id) {
    $postManager = new PostManager();
    if($post = $postManager->getPostById($id)) {
        if($post->canEdit($_SESSION['user'])) {
            $post->delete();
            header('Location: /admin/posts/success/post_deleted/');
        }
        else {
            header('Location: /admin/posts/retry/no_auth/');
        }
    }
    else {
        header('Location: /admin/posts/retry/id_post/');
    }
}

/**
 * Ajoute un nouvel article
 * 
 * @param int $id_user : Utilisateur qui ajoute l'article
 * @param boolean $published : Si l'article est à publier
 * @param string $title : Titre de l'article
 * @param string $date_publication : Date de publication au format DateTime
 * @param string $content : Contenu de l'article
 * 
 * @return void
 */
function newPost($published, string $title, string $date_publication, string $content) {
    if($_SESSION['user']->level >= User::LEVEL_EDITOR) {
        if($title != '' && Post::isDate($date_publication) && $content != '') {
            $post = Post::default();
            $post->date_publication = $date_publication;
            $post->id_user = $_SESSION['user']->id;
            $post->title = $title;
            $post->content = $content;
            $post->published = ($_SESSION['user']->level >= User::LEVEL_ADMIN)?$published:false;
            $post->save();

            header('Location: /admin/posts/success/post_added/');
        }
        else {
            header('Location: /admin/posts/new/retry/missing_fields/');
        }        
    }
    else {
        header('Location: /admin/retry/no_auth/');
    }
}

/**
 * Modifie un article existant
 * 
 * @param int $id_post : Identifiant de l'article
 * @param int $id_user : Identifiant de l'utilisateur
 * @param boolean $published : Article publié ?
 * @param string $title : Titre de l'article
 * @param string $date_publication : Date de publication au format DateTime
 * @param string $content : Contenu
 * 
 * @return void
 */
function modifyPost(int $id_post, int $id_user, $published, string $title, string $date_publication, string $content) {
    if($_SESSION['user']->level >= LEVEL_EDITOR) {
        $postManager = new PostManager();
        if($post = $postManager->getPostById($id_post)) {
            if($post->canEdit($_SESSION['user'])) {
                if($post->id_user == $id_user || $_SESSION['user']->level >= User::LEVEL_ADMIN) {
                    if($title != '' && Post::isDate($date_publication) && $content != '') {
                        $post->id_user = $id_user;
                        $post->date_publication = $date_publication;
                        $post->title = $title;
                        $post->content = $content;
                        $post->published = ($_SESSION['user']->level >= User::LEVEL_ADMIN)?$published:$post->published;
                        $post->save();

                        header("Location: /admin/posts/success/post_modified/");
                    }
                    else {
                        header("Location: /admin/posts/edit/$id_post/retry/missing_fields/");
                    }
                }
                else {
                    header("Location: /admin/posts/edit/$id_post/retry/no_auth/");
                }
            }
            else {
                header("Location: /admin/posts/retry/no_auth/");
            }
        }
        else {
            header('Location: /admin/posts/retry/id_post/');
        }
    }
    else {
        header('Location: /admin/retry/no_auth/');
    }
}