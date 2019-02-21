<?php
$title = "Mon blog";
ob_start();
?>
<h1> Le blog </h1>
<p>Derniers billets :</p>
<?php
foreach($posts as &$post) {
    $author = $post->getAuthor();
?>
    <div>
        <h3>
            <?= htmlspecialchars($post->title) ?>
            <em>le <?= $post->rDate("date_publication") ?> par <?= $author->name_display ?></em>
        </h3>

        <p>
            <?= nl2br(htmlspecialchars($post->content)) ?>
        </p>
    </div>
<?php
}
$content = ob_get_clean();
require('template.php');