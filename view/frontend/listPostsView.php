<?php
/**
 * Gère l'affichage de la liste des posts
 */
ob_start();
?>
<p>Articles :</p>
<?php
foreach($posts as $post) {
    echo $post->display(true);
}
echo $pageSelector;
$content = ob_get_clean();

require('template.php');