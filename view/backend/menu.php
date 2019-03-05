<?php
namespace DartAlex;
/**
 * Gère l'affichage du menu
 */
ob_start();
?>
<nav class="navbar-start">
    <a class="navbar-item" href="/admin/">Administration</a>
    <a class="navbar-item" href="/admin/reports/">Signalements</a>
<?php
if($_SESSION['user']->level >= User::LEVEL_EDITOR) {
?>
    <a class="navbar-item" href="/admin/posts/">Articles</a>
<?php
}
if($_SESSION['user']->level >= User::LEVEL_ADMIN) {
?>
    <a class="navbar-item" href="/admin/users/">Utilisateurs</a>
    <a class="navbar-item" href="/admin/bans/">Bans</a>   
<?php
}
?>
</nav>
<?php
$menu = ob_get_clean();