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
if(preg_match('/reply_to\//', $_GET['path'])) { // On récupère l'id du commentaire auquel on veut répondre, si il a été fourni
    $reply_to = (int) preg_replace('/^.*reply_to\/(\d+)\/.*$/', '$1', $_GET['path']);
?>
<p>Répondre au commentaire <a href="<?= preg_replace('/^(.*)reply_to\/\d+\/(.*)$/', '$1$2', $_GET['path']) ?>">Annuler</a></p>
<?php
}
else { // Sinon, on n'est pas en train de répondre à un commentaire
    $reply_to = 0;
}
/**
 * Formulaire gérant l'ajout d'un nouveau commentaire
 * Renvoie les données en post vers le chemin actuel
 * @var string action : commentPost (hidden)
 * @var string id_post : L'identifiant du post qu'on commente (hidden)
 * @var string reply_to : L'identifiant du commentaire auquel on répond (0 si le commentaire n'est pas une réponse) (hidden)
 * @var string name : Le nom de l'auteur du commentaire (readonly si l'utilisateur est identifié) (required)
 * @var string content : Corps du commentaire (required)
 */
?>
<form action="<?= $_GET['path'] ?>" method="post">
    <input type="hidden" name="action" value="commentPost"/>
    <input type="hidden" name="id_post" value="<?= $post->id ?>"/>
    <input type="hidden" name="reply_to" value="<?= $reply_to ?>"/>

    <div>

        <label for="name">Auteur</label><br/>
        <input type="text" id="name" name="name" value="<?= $_SESSION['user']->name_display ?>" required<?= ($_SESSION['user']->id != 0)?' readonly':'' ?>/>

    </div>
    <div>
        <label for="content">Commentaire</label><br/>
        <textarea id="content" name="content" required></textarea>
    </div>
    <div>
        <input type="submit"/>
    </div>
</form>
<?php
if($post->comments_nbr != 0) { // Si il y a des commentaires, on les affiche
    foreach($comments as &$comment) {
        if($comment->reply_to == 0) { // Si le commentaire n'est pas une réponse, on l'affiche
?>
<div <?= ($comment->id == $reply_to)?'style="background-color:red;"':'' // Si on est en train de répondre au commentaire, on le met en surbrillance ?>>
    <p><strong><?= htmlspecialchars($comment->getName()) ?></strong> le <?= $comment->rDate('date_publication') ?> <?= ($reply_to == 0)?'<a href="'.$_GET['path'].'reply_to/'.$comment->id.'/">Répondre</a></p>':'' ?>
    <p><?= nl2br(htmlspecialchars($comment->content)) ?></p>
</div>
<?php
            if($comment->replies_nbr != 0) { // Si il y a des réponses au commentaire, on les récupère et les affiche
                $replies = $commentManager->getReplies($comment);
                foreach($replies as &$reply) {
?>
<div style="margin-left:20px;">
    <p><strong><?= htmlspecialchars($reply->getName()) ?></strong> le <?= $reply->rDate('date_publication') ?></p>
    <p><?= nl2br(htmlspecialchars($reply->content)) ?></p>
</div>
<?php
                }
            }
        }
    }
}

$content = ob_get_clean();

require('template.php');