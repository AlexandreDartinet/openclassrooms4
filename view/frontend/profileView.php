<?php
/**
 * Gère l'affichage du profil d'un utilisateur
 */

ob_start();
?>
<div class="directory-item" id="directory-item-<?= $user->id ?>">
    <p><?= $user->name_display ?> : <?= $user->displayLevel() ?></p>
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
$content = ob_get_clean();

require('template.php');