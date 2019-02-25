<?php
/**
 * Gère l'affichage du menu
 */
ob_start();
?>
<nav>
    <a href="/">Accueil</a>
    <a href="/archive/">Archives</a>
    <a href="/directory/">Annuaire</a>
    <a href="/contact/">Contactez-nous</a>
<?php
if($_SESSION['user']->level >= User::LEVEL_MODERATOR) { // Si l'utilisateur a un niveau suffisant, on affiche le lien vers la partie backend
?>
    <a href="/admin/">Administration</a>
<?php
}
?>
</nav>
<?php
$menu = ob_get_clean();