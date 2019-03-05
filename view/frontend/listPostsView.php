<?php
namespace DartAlex;
/**
 * Gère l'affichage de la liste des posts
 */
ob_start();
?>
<h2>Articles</h2>
<?php
foreach($posts as $post) {
    echo $post->display(true);
}
echo $pageSelector;
$content = ob_get_clean();

if(!isset($scripts)) {
    $scripts = [];
}
$scripts[] = "<script src='/public/js/frontend/listPostsView.js'></script>";

require('template.php');