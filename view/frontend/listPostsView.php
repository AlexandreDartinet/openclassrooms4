<?php
namespace DartAlex;
/**
 * Gère l'affichage de la liste des posts
 */
ob_start();
?>
<h2 class="title is-2 has-text-centered">Articles</h2>
<div class="post-list">
<?php
$count = 1;
switch(sizeof($posts)) {
    case 2:
        $fullwidth = [1,2];
        break;
    case 5:
        $fullwidth = [1,4,5];
        break;
    case 8:
        $fullwidth = [1,4,7,8];
        break;
    case 11:
        $fullwidth = [1,4,7,10,11];
        break;
    case 14:
        $fullwidth = [1,4,7,10,13,14];
        break;
    case 17:
        $fullwidth = [1,4,7,10,13,16,17];
        break;
    default:
        $fullwidth = [1,4,7,10,13,16,19];
        break;
}
foreach($posts as $post) {
    
    $class = "post-bloc ".((in_array($count, $fullwidth))?"is-full":"is-half");
    $title = htmlspecialchars($post->title);
    $author = $post->user->displayName();
    $date = $post->rDate('date_publication');
    $count += 1;
?>
<div class="<?= $class ?>" id="post-<?= $post->id ?>">
    <div class="post-cover" style="background-image: url('<?= $post->getImage() ?>');">
        <a href="/post/<?= $post->id ?>/"></a>
    </div>
    <div class="post-extract">
        <p class="title is-4 is-spaced"><a href="/post/<?= $post->id ?>/"><?= $title ?></a></p>
        <p class="subtitle is-6 has-text-grey-light is-spaced"><?= $date ?> par <?= $author ?></p>
        <div class="extract">
            <?= $post->getExtract() ?>
        </div>
        <div class="bottom">
            <div class="icon comments"><i class="fas fa-comments"></i>&nbsp;<?= $post->comments_nbr ?></div>
            <div class="more"><a href="/post/<?= $post->id ?>/">Lire la suite...</a></div>
        </div>
    </div>
</div>


<?php
}
?>
</div>
<?php
echo '<div class="container" style="max-width:960px;">'.$pageSelector.'</div>';
$content = ob_get_clean();

if(!isset($scripts)) {
    $scripts = [];
}
$scripts[] = "<script src='/public/js/frontend/listPostsView.js'></script>";

require('template.php');