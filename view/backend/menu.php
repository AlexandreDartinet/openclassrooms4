<?php
/**
 * Gère l'affichage du menu
 */
ob_start();
?>
<nav>
    <a href="/">Accueil</a>
    <a href="/admin/reports/">Signalements</a>
<?php
if($_SESSION['user']->level >= User::LEVEL_EDITOR) {
?>
    <a href="/admin/posts/">Articles</a>
<?php
}
if($_SESSION['user']->level >= User::LEVEL_ADMIN) {
?>
    <a href="/admin/users/">Utilisateurs</a>
    <a href="/admin/bans/">Bans</a>   
<?php
}
?>
</nav>
<?php
$menu = ob_get_clean();