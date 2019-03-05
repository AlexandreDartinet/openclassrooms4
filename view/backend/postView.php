<?php
namespace DartAlex;
/**
 * Gère l'affichage de l'édition d'un article
 */
ob_start();
?>
<h2 class="title is-3"><?= ($new)?"Nouvel article":"Edition de l'article $post->id" ?></h2>

<?php
if($new) {
    $action = "newPost";
    $id_post = 0;
    $id_user = $_SESSION['user']->id;
    $date = Post::dateToHtml(Post::now());
    $published = false;
    $title = "";
    $content = "";
}
else {
    $action = "modifyPost";
    $id_post = $post->id;
    $id_user = $post->id_user;
    $date = Post::dateToHtml($post->date_publication);
    $published = $post->published;
    $title = $post->title;
    $content = $post->content;
}

?>
<div class="box">
    <form method="post" action="/admin/posts/">
        <input type="hidden" name="action" value="<?= $action ?>"/>
        <input type="hidden" name="id_post" value="<?= $id_post ?>"/>
        <input type="hidden" name="id_user" value="<?= $id_user ?>"/>
        <div class="field is-horizontal">
            <div class="field-label is-normal">
                <label class="label" for="title">Titre</label>
            </div>
            <div class="field-body">
                <div class="field">
                    <div class="control">
                        <input class="input" type="text" name="title" id="title" value="<?= $title ?>" required/>
                    </div>
                </div>
            </div> 
        </div>
        <div class="field is-horizontal">
            <div class="field-label is-normal">
                <label for="date_publication" class="label">Date de publication</label>
            </div>
            <div class="field-body">
                <div class="field">
                    <div class="control">
                        <input class="input" type="datetime-local" name="date_publication" id="date_publication" value="<?= $date ?>" required/>
                    </div>
                </div>
            </div> 
        </div>
        <div class="field">
            <div class="control">
                <textarea class="textarea" name="content" id="content" required><?= $content ?></textarea>
            </div>
        </div>
<?php
if($_SESSION['user']->level >= User::LEVEL_ADMIN) {
?>
        <div class="field">
            <div class="control">
                <label class="checkbox">
                <input type="checkbox" name="published"<?= (($published)?" checked":"") ?>/>
                    Publié
                </label>
            </div>
        </div>

<?php
}
elseif($published) {
?>
        <input type="hidden" name="published" value="on"/>
<?php
}
?>
        <div class="field is-grouped is-grouped-centered">
            <input class="button is-primary" type="submit" value="Enregistrer"/>
        </div>
    </form>
</div>
<?php
$scripts = [
    "<script src='https://cloud.tinymce.com/5/tinymce.min.js?apiKey=".TINYMCE_KEY."'></script>",
    '<script src="/public/js/TinyMCE.js"></script>'
];
$content = ob_get_clean();
require('template.php');