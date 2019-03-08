<?php
namespace DartAlex;
/**
 * Gère l'affichage de la liste des signalements
 */
ob_start();
?>
<h2 class="title is-3">Liste des commentaires signalés</h2>
<div class="container">
<?php
foreach($comments as $comment) {
    echo $comment->display(true,false,false,true);
}
echo $pageSelector;
echo '</div>';

$content = ob_get_clean();

require('template.php');