<?php 
/**
 * Template d'affichage de la page frontend.
 * On doit lui fournir $title pour le titre de la page, et $content pour son contenu.
 */
require('header.php');
require('footer.php'); 
ob_start();
?>
        <?= $header ?>
        <?= $content ?>
        <?= $footer ?>
<?php
$body = ob_get_clean();

require('view/template.php');
