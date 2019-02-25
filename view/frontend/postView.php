<?php 
/**
 * Gère l'affichage d'un post
 */
$title = htmlspecialchars($post->title); 
ob_start();
?>
<div>
    <h3>
        <?= htmlspecialchars($post->title) ?>
        <em>le <?= $post->rDate('date_publication') ?> par <?= $post->user->displayName() ?></em>
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
<?= $reply_to_comment->display(false, true, false) ?>
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
            echo $comment->display((($reply_to == 0) && !$edit), ($comment->id == $reply_to));
            if($comment->replies_nbr != 0) { // Si il y a des réponses au commentaire, on les récupère et les affiche
                foreach($comment->replies as $reply) {
                    echo $reply->display((($reply_to == 0) && !$edit), ($reply->id == $reply_to));
                }
            }
        }
    }
echo $pageSelector;
}

$content = ob_get_clean();

require('template.php');