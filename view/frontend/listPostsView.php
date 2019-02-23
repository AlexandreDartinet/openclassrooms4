<?php
/**
 * Gère l'affichage de la liste des posts
 */
$title = "Mon blog";
ob_start();
?>
<p>Derniers billets :</p>
<?php
foreach($posts as &$post) {
    $author = $post->getAuthor();
?>
    <div>
        <h3><a href="/post/<?= $post->id ?>/">
            <?= htmlspecialchars($post->title) ?>
            <em>le <?= $post->rDate("date_publication") ?> par <?= $author->name_display ?></em>
        </a></h3>

        <p>
            <?= nl2br(htmlspecialchars($post->getExtract())) ?>
            <br/>
            <em><a href="/post/<?= $post->id ?>/">Commentaires(<?= $post->comments_nbr ?>)</a></em>
        </p>
    </div>

<?php
}
$content = ob_get_clean();

require('template.php');