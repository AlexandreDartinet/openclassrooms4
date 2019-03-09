<?php 
namespace DartAlex;
/**
 * Gère l'affichage d'un post
 */
ob_start();
if($start = strpos($post->content, '<img')) {
    $end = strpos($post->content, '>', $start);
    $content = substr($post->content, 0, $start).substr($post->content, $end+1, strlen($post->content));
}
else {
    $content = $post->content;
}
?>
<div class="post">
    <div class="title-container">
        <div class="background-cover" style="background-image: url('<?= $post->getImage() ?>');">
            <div class="background-shroud">
                <p class="title">
                    <?= htmlentities($post->title) ?>
                </p>
                <p class="date">
                    Publié le <?= $post->rDate('date_publication') ?> par <?= $post->user->displayName() ?>
                </p>
                <p class="chevron bounce">
                    <i class="fas fa-chevron-down"></i>
                </p>
            </div>
        </div>
    </div>
    <article class="container">
        <?=  $content ?>
    </article>
</div>
<aside id="comments" class="box">
    <h2 class="title is-3">Commentaires</h2>
    <div id="comment-form-div" class="box">
<?php
if($edit){
?>
        <p>Editer le commentaire <a id="comments-cancel-edit" href="<?= preg_replace('/edit\/\d+\//', '', PATH) ?>">Annuler</a></p>
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
        <p>Répondre au commentaire <a id="comments-cancel-reply" href="<?= preg_replace('/reply_to\/\d+\//', '', PATH) ?>">Annuler</a></p>
        <?= $reply_to_comment->display(false, true, false) ?>
<?php
    }
    $action = "commentPost";
    $commentId = 0;
    $commentName = $_SESSION['user']->name_display;
    $commentContent = '';
}
if($_SESSION['user']->canComment()) {
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

            <div class="field">

                <label for="name" class="label">Auteur</label>
                <div class="control">
                    <input class="input<?= ($_SESSION['user']->id != 0)?' is-static':'' ?>" type="text" id="name" name="name" value="<?= $commentName ?>" required<?= ($_SESSION['user']->id != 0)?' readonly':'' ?>/>
                </div>
            </div>
            <div class="field">
                <label for="content" class="label">Commentaire</label>
                <div class="control">
                    <textarea class="textarea" id="content" name="content" required><?= $commentContent ?></textarea>
                </div>
            </div>
            <div class="field is-grouped is-grouped-centered">
                <input class="button is-primary" type="submit" value="Envoyer"/>
            </div>
        </form>
<?php
}
?>
</div>
<div id="comments-div">
<?php
if($isComments) { // Si il y a des commentaires, on les affiche
    foreach($comments as &$comment) {
        if($comment->reply_to == 0) { // Si le commentaire n'est pas une réponse, on l'affiche
            echo $comment->display((($reply_to == 0) && !$edit), ($comment->id == $reply_to));
            if($comment->replies_nbr != 0) { // Si il y a des réponses au commentaire, on les récupère et les affiche
                foreach($comment->replies as $reply) {
                    echo $reply->display((($reply_to == 0) && !$edit), ($reply->id == $reply_to));
                }
            }
        }
    }
}
?>
</div>
<?= $pageSelector ?></aside>
<?php

$content = ob_get_clean();
if(!isset($scripts)) {
    $scripts = [];
}
ob_start();
?>
<script src="/public/js/message.js"></script>
<script src="/public/js/htmlFunctions.js"></script>
<script src="/public/js/pageSelector.js"></script>
<script>
    const postId = <?= $post->id ?>;
</script>
<script src="/public/js/postView.js"></script>
<?php
$scripts[] = ob_get_clean();


require('template.php');