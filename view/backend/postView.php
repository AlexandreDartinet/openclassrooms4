<?php
/**
 * Gère l'affichage de l'édition d'un article
 */
ob_start();
?>
<h2><?= ($new)?"Nouvel article":"Edition de l'article $post->id" ?></h2>

<?php
if($new) {
    $action = "newPost";
    $id_user = $_SESSION['user'];
    $date = Post::dateToHtml(Post::now());
    $published = false;
    $title = "";
    $content = "";
}
else {
    $action = "modifyPost";
    $id_user = $post->id_user;
    $date = Post::dateToHtml($post->date_publication);
    $published = $post->published;
    $title = $post->title;
    $content = $post->content;
}

?>
<form method="post" action="/admin/posts/">
    <input type="hidden" name="action" value="<?= $action ?>"/>
    <input type="hidden" name="id_user" value="<?= $id_user ?>"/>
<?php
if($_SESSION['user']->level >= User::LEVEL_ADMIN) {
?>
    <div><input type="checkbox" name="published"<?= (($published)?" checked":"") ?>/>Publié</div>

<?php
}
else {
?>
    <input type="hidden" name="published" value="<?= (($published)?"on":"") ?>"/>
<?php
}
?>
    <div>
        <label for="title">Titre : </label>
        <input type="text" name="title" id="title" value="<?= $title ?>" required/>
    </div>
    <div>
        <label for="date_publication">Date de publication : </label>
        <input type="datetime-local" name="date_publication" id="date_publication" value="<?= $date ?>" required/>
    </div>
    <div>
        <textarea name="content" id="content" required><?= $content ?></textarea>
    </div>
    <!--<input type="submitbtn"/>-->
</form>
<?php
$scripts = [
    "<script src='https://cloud.tinymce.com/5/tinymce.min.js?apiKey=".TINYMCE_KEY."'></script>",
    '<script src="/public/js/editPost.js"></script>'
];
$content = ob_get_clean();
require('template.php');