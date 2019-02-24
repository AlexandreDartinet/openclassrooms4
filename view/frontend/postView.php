<?php 
/**
 * Gère l'affichage d'un post
 */
$title = htmlspecialchars($post->title); 
$author = $post->getAuthor();
ob_start();
?>
<div>
    <h3>
        <?= htmlspecialchars($post->title) ?>
        <em>le <?= $post->rDate('date_publication') ?> par <?= $author->name_display ?></em>
    </h3>

    <p>
        <?= nl2br(htmlspecialchars($post->content)) ?>
    </p>
</div>
<h2>Commentaires</h2>
<?php
if($edit){
?>
<p>Editer le commentaire <a href="<?= preg_replace('/edit\/\d+\//', '', PATH) ?>">Annuler</a></p>
<?php
    $action = "modifyComment";
    $reply_to = $editedComment->reply_to;
    $commentId = $editedComment->id;
    $commentName = $editedComment->getName();
    $commentContent = $editedComment->content;
}
else {
    if($reply_to > 0) { // Si on répond à un commentaire
?>
<p>Répondre au commentaire <a href="<?= preg_replace('/reply_to\/\d+\//', '', PATH) ?>">Annuler</a></p>
<div>
    <p><strong><?= htmlspecialchars($reply_to_comment->getName()) ?></strong> le <?= $reply_to_comment->rDate('date_publication') ?>
    <p><?= nl2br(htmlspecialchars($reply_to_comment->content)) ?></p>
</div>
<?php
    }
    $action = "commentPost";
    $commentId = 0;
    $commentName = $_SESSION['user']->name_display;
    $commentContent = '';
}
/**
 * Formulaire gérant l'ajout d'un nouveau commentaire
 * Renvoie les données en post vers le chemin du post actuel
 * @var string action : commentPost ou modifyComment (hidden)
 * @var string id_post : L'identifiant du post qu'on commente (hidden)
 * @var string reply_to : L'identifiant du commentaire auquel on répond (0 si le commentaire n'est pas une réponse) (hidden)
 * @var string id : L'identifiant du commentaire (0 si nouveau commentaire)
 * @var string name : Le nom de l'auteur du commentaire (readonly si l'utilisateur est identifié) (required)
 * @var string content : Corps du commentaire (required)
 */
?>
<form action="/post/<?= $post->id ?>/" method="post">
    <input type="hidden" name="action" value="<?= $action ?>"/>
    <input type="hidden" name="id_post" value="<?= $post->id ?>"/>
    <input type="hidden" name="reply_to" value="<?= $reply_to ?>"/>
    <input type="hidden" name="id" value="<?= $commentId ?>"/>

    <div>

        <label for="name">Auteur</label><br/>
        <input type="text" id="name" name="name" value="<?= $commentName ?>" required<?= ($_SESSION['user']->id != 0)?' readonly':'' ?>/>

    </div>
    <div>
        <label for="content">Commentaire</label><br/>
        <textarea id="content" name="content" required><?= $commentContent ?></textarea>
    </div>
    <div>
        <input type="submit"/>
    </div>
</form>
<?php
if($isComments) { // Si il y a des commentaires, on les affiche
    foreach($comments as &$comment) {
        if($comment->reply_to == 0) { // Si le commentaire n'est pas une réponse, on l'affiche
?>
<div class="comment <?= ($comment->id == $reply_to)?' reply_to':'' // Si on est en train de répondre au commentaire, on le met en surbrillance ?>" id="comment-<?= $comment->id ?>">
    <p>
        <strong><?= htmlspecialchars($comment->getName()) ?></strong> le <?= $comment->rDate('date_publication') ?> 
        <?= (($reply_to == 0) && !$edit)?'<a class="comment-reply-link" id="comment-reply-link-'.$comment->id.'" href="'.PATH.'reply_to/'.$comment->id.'/">Répondre</a>':'' ?>
        <?= ($comment->canEdit($_SESSION['user']) && ($reply_to == 0) && !$edit)?'<a class="comment-edit-link" id="comment-edit-link-'.$comment->id.'" href="'.PATH.'edit/'.$comment->id.'/">Editer</a> <a class="comment-delete-link" id="comment-delete-link-'.$comment->id.'" href="'.PATH.'delete/'.$comment->id.'/">Supprimer</a>':'' ?>
    </p>
    <p><?= nl2br(htmlspecialchars($comment->content)) ?></p>
</div>
<?php
            if($comment->replies_nbr != 0) { // Si il y a des réponses au commentaire, on les récupère et les affiche
                $replies = $commentManager->getReplies($comment);
                foreach($replies as &$reply) {
?>
<div class="comment reply" id="comment-<?= $reply->id ?>">
    <p>
        <strong><?= htmlspecialchars($reply->getName()) ?></strong> le <?= $reply->rDate('date_publication') ?>
        <?= ($reply->canEdit($_SESSION['user']) && ($reply_to == 0) && !$edit)?'<a class="comment-edit-link" id="comment-edit-link-'.$reply->id.'" href="'.PATH.'edit/'.$reply->id.'/">Editer</a> <a class="comment-delete-link" id="comment-delete-link-'.$reply->id.'" href="'.PATH.'delete/'.$reply->id.'/">Supprimer</a>':'' ?>
    </p>
    <p><?= nl2br(htmlspecialchars($reply->content)) ?></p>
</div>
<?php
                }
            }
        }
    }
echo $pageSelector;
}

$content = ob_get_clean();

require('template.php');