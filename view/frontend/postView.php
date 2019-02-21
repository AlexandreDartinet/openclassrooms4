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

<form action="<?= $_GET['path'] ?>" method="post">
    <input type="hidden" name="action" value="commentPost" required/>
    <input type="hidden" name="id_post" value="<?= $post->id ?>" required/>

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
?>
<p><strong><?= htmlspecialchars($comment->getName()) ?></strong> le <?= $comment->rDate('date_publication') ?></p>
<p><?= nl2br(htmlspecialchars($comment->content)) ?></p>


<?php
    }
}

$content = ob_get_clean();
require('template.php');