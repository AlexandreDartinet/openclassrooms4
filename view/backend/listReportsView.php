<?php
namespace DartAlex;
/**
 * Gère l'affichage de la liste des signalements
 */
ob_start();
?>
<h2>Liste des commentaires signalés :</h2>
<?php
foreach($comments as $comment) {
    echo $comment->display(true,false,false,true);
}
echo $pageSelector;

$content = ob_get_clean();

require('template.php');