<?php 
$title = htmlspecialchars($post->title); 
$author = $post->getAuthor();
ob_start();
?>
<p><a href="/">Retour à l'accueil</a></p>

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
if(preg_match('/reply_to\//', $_GET['path'])) {
    $reply_to = (int) preg_replace('/^.*reply_to\/(\d+)\/.*$/', '$1', $_GET['path']);
?>
<p>Répondre au commentaire <a href="<?= preg_replace('/^(.*)reply_to\/\d+\/(.*)$/', '$1$2', $_GET['path']) ?>">Annuler</a></p>
<?php
}
else {
    $reply_to = 0;
}
?>
<form action="<?= $_GET['path'] ?>" method="post">
    <input type="hidden" name="action" value="commentPost" required/>
    <input type="hidden" name="id_post" value="<?= $post->id ?>" required/>
    <input type="hidden" name="reply_to" value="<?= $reply_to ?>" required/>

    <div>
        <label for="name">Auteur</label><br/>
        <input type="text" id="name" name="name" value="<?= $_SESSION['user']->name_display ?>" required/>
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
if($post->comments_nbr != 0) {
    foreach($comments as &$comment) {
        if($comment->reply_to == 0) {
?>
<div <?= ($comment->id == $reply_to)?'style="background-color:red;"':'' ?>>
    <p><strong><?= htmlspecialchars($comment->getName()) ?></strong> le <?= $comment->rDate('date_publication') ?> <?= ($reply_to == 0)?'<a href="'.$_GET['path'].'reply_to/'.$comment->id.'/">Répondre</a></p>':'' ?>
    <p><?= nl2br(htmlspecialchars($comment->content)) ?></p>
</div>
<?php
            if($comment->replies_nbr != 0) {
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