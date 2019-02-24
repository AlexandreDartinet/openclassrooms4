<?php
/**
 * Gère l'affichage de la liste des posts
 */
ob_start();
?>
<p>Articles :</p>
<?php
foreach($posts as &$post) {
?>
    <div>
        <h3><a href="/post/<?= $post->id ?>/">
            <?= htmlspecialchars($post->title) ?>
            <em>le <?= $post->rDate("date_publication") ?> par <?= $post->user->displayName() ?></em>
        </a></h3>

        <p>
            <?= nl2br(htmlspecialchars($post->getExtract())) ?>
            <br/>
            <em><a href="/post/<?= $post->id ?>/">Commentaires(<?= $post->comments_nbr ?>)</a></em>
        </p>
    </div>

<?php
}
echo $pageSelector;
$content = ob_get_clean();

require('template.php');