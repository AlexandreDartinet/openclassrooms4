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
        $json->comments = [];
        $json->commentsNbr = sizeof($comments);
        $json->canComment = $_SESSION['user']->canComment();
        $json->commentPage = CommentManager::COMMENT_PAGE;
        $moderator = ($_SESSION['user']->level >= User::LEVEL_MODERATOR);
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
                $reply_json->replyTo = $comment->reply_to;
                $reply_json->canEdit = $comment->canEdit($_SESSION['user']);
                $reply_json->replies = [];
                $reply_json->repliesNbr = 0;
                $reply_json->isReply = true;
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