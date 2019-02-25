<?php
/**
 * Gère l'affichage de l'annuaire
 */
ob_start();
?>
<h2>Utilisateurs inscrits sur le site</h2>
<?php
foreach($users as &$user) {
?>
<div class="directory-item" id="directory-item-<?= $user->id ?>">
    <p><?= $user->displayName() ?> : <?= $user->displayLevel() ?></p>
    <p>Enregistrement : <?= $user->rDate('date_inscription') ?></p>
    <p>Dernière connexion : <?= $user->rDate('last_seen') ?></p>
    <p>Commentaires postés : <?= $user->comments_nbr ?></p>
<?php
    if($user->level >= User::LEVEL_EDITOR) {
?>
    <p>Articles publiés : <?= $user->posts_nbr ?></p>
<?php
    }
?>
</div>
<?php
}
echo $pageSelector;
$content = ob_get_clean();

require('template.php');