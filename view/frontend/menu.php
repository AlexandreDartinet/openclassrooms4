<?php
namespace DartAlex;
/**
 * Gère l'affichage du menu
 */
ob_start();
?>
<nav class="navbar-start">
    <a class="navbar-item" href="/archive/"><span class="icon"><i class="fas fa-archive"></i></span>&nbsp;Archives</a>
    <a class= "navbar-item" href="/directory/"><span class="icon"><i class="fas fa-address-book"></i></span>&nbsp;Annuaire</a>
    <a class="navbar-item" href="/contact/"><span class="icon"><i class="fas fa-envelope"></i></span>&nbsp;Contactez-nous</a>
<?php
if($_SESSION['user']->level >= User::LEVEL_MODERATOR) { // Si l'utilisateur a un niveau suffisant, on affiche le lien vers la partie backend
?>
    <a class="navbar-item" href="/admin/">Administration</a>
<?php
}
?>
</nav>
<?php
$menu = ob_get_clean();