<?php
/**
 * Controlleur pour les requêtes ajax
 */

/**
 * Renvoie la liste des commentaires d'un post
 * 
 * @param int $id : Id du post
 * 
 * @return void
 */
function displayCommentsJson(int $id) {
    $postManager = new PostManager();
    if($postManager->exists('id', $id)) {
        $commentManager = new CommentManager();
        $comments = $commentManager->getComments($id, "all");
        $json = new stdClass();
        $json->lastId = $commentManager->lastId('id_post', $id);
        $json->user = new stdClass();
        $json->user->id = $_SESSION['user']->id;
        $json->user->name = $_SESSION['user']->name_display;
        $json->user->ip = $_SESSION['user']->ip;
        $json->user->canComment = $_SESSION['user']->canComment();
        $json->commentsNbr = sizeof($comments);
        $json->commentPage = CommentManager::COMMENT_PAGE;
        $json->reportTypes = Report::TYPES;
        $moderator = ($_SESSION['user']->level >= User::LEVEL_MODERATOR);
        $json->comments = [];
        foreach($comments as $comment) {
            $comment_json = new stdClass();
            if($moderator) {
                $comment_json->ip = $comment->ip;
            }
            $comment_json->date = $comment->rDate('date_publication');
            $comment_json->id = $comment->id;
            $comment_json->author = new stdClass();
            $comment_json->author->id = $comment->id_user;
            $comment_json->author->name = $comment->getName();
            $comment_json->author->nameDisplay = $comment->displayName();
            $comment_json->content = $comment->content;
            $comment_json->replyTo = $comment->reply_to;
            $comment_json->canEdit = $comment->canEdit($_SESSION['user']);
            $comment_json->isReply = false;
            if($moderator && $comment->reports_nbr > 0) {
                $comment_json->reportsNbr = $comment->reports_nbr;
            }
            $comment_json->replies = [];
            foreach($comment->replies as $reply) {
                $reply_json = new stdClass();
                if($moderator) {
                    $reply_json->ip = $reply->ip;
                }
                $reply_json->date = $reply->rDate('date_publication');
                $reply_json->id = $reply->id;
                $reply_json->userId = $reply->id_user;
                $reply_json->author = new stdClass();
                $reply_json->author->id = $reply->id_user;
                $reply_json->author->name = $reply->getName();
                $reply_json->author->nameDisplay = $reply->displayName();
                $reply_json->content = $reply->content;
                $reply_json->replyTo = $reply->reply_to;
                $reply_json->canEdit = $reply->canEdit($_SESSION['user']);
                $reply_json->replies = [];
                $reply_json->repliesNbr = 0;
                $reply_json->isReply = true;
                if($moderator && $reply->reports_nbr > 0) {
                    $reply_json->reportsNbr = $reply->reports_nbr;
                }
                $comment_json->replies[] = $reply_json;
            }
            $comment_json->repliesNbr = $comment->replies_nbr;
            $json->comments[] = $comment_json;
        }
        displayJson($json);
    }
    else {
        displayErrorJson('ajax_unknown_post');
    }
}

/**
 * Retourne les commentaires après un certain identifiant
 * 
 * @param int $id_post : Identifiant du post
 * @param int $last_id : Dernier identifiant
 * 
 * @return void
 */
function updateCommentsJson(int $id_post, int $last_id) {
    $postManager = new PostManager();
    if($postManager->exists('id', $id_post)) {
        $commentManager = new CommentManager();
        $comments = $commentManager->getCommentsAfter($id_post, $last_id);
        $json = new stdClass();
        $json->lastId = $commentManager->lastId('id_post', $id_post);
        $json->commentsNbr = sizeof($comments);
        $moderator = ($_SESSION['user']->level >= User::LEVEL_MODERATOR);
        $json->comments = [];
        foreach($comments as $comment) {
            $comment_json = new stdClass();
            if($moderator) {
                $comment_json->ip = $comment->ip;
            }
            $comment_json->date = $comment->rDate('date_publication');
            $comment_json->id = $comment->id;
            $comment_json->author = new stdClass();
            $comment_json->author->id = $comment->id_user;
            $comment_json->author->name = $comment->getName();
            $comment_json->author->nameDisplay = $comment->displayName();
            $comment_json->content = $comment->content;
            $comment_json->replyTo = $comment->reply_to;
            $comment_json->canEdit = $comment->canEdit($_SESSION['user']);
            $comment_json->isReply = ($comment->reply_to != 0);
            if($moderator && $comment->reports_nbr > 0) {
                $comment_json->reportsNbr = $comment->reports_nbr;
            }
            $comment_json->replies = [];
            $comment_json->repliesNbr = 0;
            $json->comments[] = $comment_json;
        }
        displayJson($json);
    }
    else {
        displayErrorJson('ajax_unknown_post');
    }
}

/**
 * Enregistre un nouveau commentaire
 * 
 * @param int $id_post : Identifiant du post
 * @param int $reply_to : Réponse à
 * @param string $name : Nom de l'expéditeur
 * @param string $content : Contenu
 * 
 * @return void
 */
function sendComment(int $id_post, int $reply_to, string $name, string $content) {
    if($_SESSION['user']->canComment()) {
        $postManager = new PostManager();
        if($postManager->exists('id', $id_post)) {
            if($reply_to != 0) {
                $commentManager = new CommentManager();
                if(!$commentManager->exists('id', $reply_to)) {
                    displayErrorJson("Le commentaire auquel vous essayez de répondre n'existe pas.");
                    return;
                }
            }
            if($name != "" && $content != "") {
                $comment = Comment::default();
                $comment->id_post = $id_post;
                $comment->reply_to = $reply_to;
                $comment->name = $name;
                $comment->content = $content;
                $comment->id_user = $_SESSION['user']->id;
                $comment->save();
                displaySuccessJson("Commentaire envoyé.");
            }
            else {
                displayErrorJson("Tous les champs ne sont pas remplis.");
            }
        }
        else {
            displayErrorJson("L'article que vous essayez de commenter n'existe pas.");
        }
    }
    else {
        displayErrorJson("Vous n'avez pas le droit de commenter.");
    }
}

/**
 * Modifie un commentaire existant
 * 
 * @param int $id : Identifiant du commentaire
 * @param string $name : Nom de l'expéditeur
 * @param string $content : Contenu du commentaire
 * 
 * @return void
 */
function modifyComment(int $id, string $name, string $content) {
    if($_SESSION['user']->canComment()) {
        $commentManager = new CommentManager();
        if($comment = $commentManager->getCommentById($id)) {
            if($comment->canEdit($_SESSION['user'])) {
                if($comment->name != $name || $comment->content != $content) {
                    $comment->name = $name;
                    $comment->content = $content."\nModifié le ".Comment::rNow().".";
                    $comment->save();
                    displaySuccessJson("Commentaire modifié avec succès.");
                }
                else {
                    displayErrorJson("Vous n'avez pas modifié le commentaire.");
                }
            }
            else {
                displayErrorJson("Vous n'avez pas le droit de modifier ce commentaire.");
            }
        }
        else {
            displayErrorJson("Le commentaire que vous essayez de modifier n'existe pas.");
        }
    }
    else {
        displayErrorJson("Vous n'avez pas le droit de commenter.");
    }
}

/**
 * Supprime un commentaire
 * 
 * @param int $id
 * 
 * @return void
 */
function deleteComment(int $id) {
    if($_SESSION['user']->canComment()) {
        $commentManager = new CommentManager();
        if($comment = $commentManager->getCommentById($id)) {
            if($comment->canEdit($_SESSION['user'])) {
                $comment->delete();
                displaySuccessJson("Commentaire supprimé.");
            }
            else {
                displayErrorJson("Vous n'avez pas le droit de supprimer le commentaire.");
            }
        }
        else {
            displayErrorJson("Le commentaire que vous essayez de supprimer n'existe pas.");
        }
    }
    else {
        displayErrorJson("Vous n'avez pas le droit de commenter.");
    }
}

/**
 * Envoie un nouveau signalement
 * 
 * @param int $id_comment
 * @param int $type
 * @param string $content
 * 
 * @return void
 */
function sendReport(int $id_comment, int $type, string $content) {
    if($_SESSION['user']->canComment()) {
        $commentManager = new CommentManager();
        if($commentManager->exists('id', $id_comment)) {
            $report = Report::default();
            $report->id_user = $_SESSION['user']->id;
            $report->ip = $_SERVER['REMOTE_ADDR'];
            $report->type = $type;
            $report->content = (($content != '')?$content:"Aucun commentaire.");
            $report->save();
            displaySuccessJson("Votre signalement a été envoyé.");
        }
        else {
            displayErrorJson("Le commentaire que vous essayez de signaler n'existe pas.");
        }
    }
    else {
        displayErrorJson("Vous n'avez pas le droit de commenter.");
    }
}