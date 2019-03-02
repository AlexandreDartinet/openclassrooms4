<?php
/**
 * Gère l'affichage du menu
 */
ob_start();
?>
<nav>
    <a class="fas fa-home" href="/"> Accueil</a>
    <a class="fas fa-archive" href="/archive/"> Archives</a>
    <a class= "fas fa-address-book" href="/directory/"> Annuaire</a>
    <a class="fas fa-envelope" href="/contact/"> Contactez-nous</a>
    <a class="fa fa-bars icon" href="javascript:void(0);" onClick="responsiveMenu()"></a>
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