<?php
/**
 * Gère l'affichage du formulaire de signalement
 * On lui fournit $comment, le commentaire à signaler et $path ou envoyer les données
 */
$title = "Signaler le commentaire de ".htmlspecialchars($comment->getName());
ob_start();
echo $comment->display(false, false, false);
/**
 * Formulaire gérant l'envoi d'un signalement de commentaire.
 * Renvoie les données à $path en post
 * @var string action : sendReport
 * @var string id_comment : Identifiant du commentaire qu'on signale
 * @var string type : Type du signalement (par défaut autre, 0) (required)
 * @var string content : Commentaire du signalement
 */
?>
<form method="post" action="<?= $path ?>">
    <input type="hidden" name="action" value="sendReport"/>
    <input type="hidden" name="id_comment" value="<?= $comment->id ?>"/>
    <div>
        <label for="type">Type de signalement :</label><br/>
        <select name="type" id="type" required>
<?php
foreach(Report::TYPES as $type => $display) {
?>
            <option value="<?= $type ?>"<?= (($type == Report::TYPE_OTHER)?' selected':'') ?>><?= $display ?></option>
<?php
}
?>
        </select>
    </div>
    <div>
        <label for="content">Commentaire du signalement :</label><br/>
        <textarea name="content" placeholder="Aucun commentaire"></textarea>
    </div>
    <input type="submit"/>
</form>
<?php
$content = ob_get_clean();

require('template.php');