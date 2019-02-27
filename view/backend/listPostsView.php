<?php
/**
 * Gère l'affichage de la liste des articles dans la section admin
 */
ob_start();
?>
<h2>Liste des articles :</h2>
<p><a href="/admin/posts/new/">Créer un nouvel article</a></p>
<table>
    <thead>
        <tr>
            <th>Titre</th>
            <th>Auteur</th>
            <th>Date de publication</th>
            <th>Publié</th>
            <th>Éditer</th>
            <th>Supprimer</th>
        </tr>
    </thead>
    <tbody>
<?php
foreach($posts as $post) {
?>
        <tr class="post-admin-row" id="post-<?= $post->id ?>">
            <td>
                <a href="/post/<?= $post->id ?>/"><?= htmlspecialchars($post->title) ?></a>
            </td>
            <td>
                <?= $post->user->displayName() ?>
            </td>
            <td>
                <?= $post->rDate('date_publication') ?>
            </td>
            <td>
<?php
    if($post->published) {
        if($post->canEdit($_SESSION['user'])) {
?>
                <a title="Masquer l'article" class="fas fa-check post-unpublish-link post-unpublish-link-active" id="post-unpublish-link-<?= $post->id ?>" href="/admin/posts/unpublish/<?= $post->id ?>/"></a>
<?php
        }
        else {
?>
                <a class="fas fa-check post-unpublish-link"></a>
<?php
        }
    }
    else {
        if($_SESSION['user']->level >= User::LEVEL_ADMIN) {
?>
                <a title="Publier l'article" class="fas fa-times post-publish-link post-publish-link-active" id="post-publish-link-<?= $post->id ?>" href="/admin/posts/publish/<?= $post->id ?>/"></a>
<?php
        }
        else {
?>
                <a class="fas fa-times post-publish-link"></a>
<?php
        }
    }
?>
            </td>
            <td>
                <?= ($post->canEdit($_SESSION['user']))?"<a class='fas fa-edit post-edit-link' id='post-edit-link-$post->id' href='/admin/posts/edit/$post->id/'></a>":"" ?>
            </td>
            <td>
                <?= ($post->canEdit($_SESSION['user']))?"<a class='fas fa-trash post-delete-link' id='post-delete-link-$post->id' href='/admin/posts/delete/$post->id/'></a>":"" ?>
            </td>
        </tr>
<?php
}
?>
    </tbody>
</table>
<?php
echo $pageSelector;
$content = ob_get_clean();

require('template.php');