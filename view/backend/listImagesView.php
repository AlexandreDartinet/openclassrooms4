<?php
namespace DartAlex;
/**
 * Gère l'affichage de la liste des images et l'envoi de nouvelles
 */
ob_start(); 
?>
<h2 class="title is-3">Liste des images</h2>
<div class="box container">
    <form class="box" action="<?= PATH ?>" method="post" enctype="multipart/form-data">
        <input type="hidden" name="action" value="addImage"/>
        <div class="field is-horizontal">
            <div class="field-label is-normal">
                <label for="title" class="label">Titre de l'image</label>
            </div>
            <div class="field-body">
                <div class="field">
                    <div class="control is-expanded">
                        <input class="input" type="text" name="title" id="title" required/>
                    </div>
                </div>
            </div>
            
        </div>
        <div class="field is-horizontal">
            <div class="field-label is-normal">
                <label class="label">Fichier</label>
            </div>
            <div class="field-body">
                <div class="file has-name">
                    <label class="file-label" for="file">
                        <input class="file-input" type="file" name="file" id="file" required/>
                        <div class="file-cta">
                            <div class="file-icon">
                                <i class="fas fa-upload"></i>
                            </div>
                            <div class="file-label">
                                Choisir un fichier...
                            </div>
                        </div>
                        <div class="file-name" id="file-name">
                            Aucun fichier choisi.
                        </div>
                    </label>
                </div>
            </div>
        </div>
        <div class="field is-grouped is-grouped-centered">
            <div class="control">
                <input class="button is-primary" value="Envoyer" type="submit"/>
            </div>
        </div>
        
        
    </form>
    <table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
        <thead>
            <th>Image</th>
            <th>Utilisateur</th>
            <th>Envoyée le</th>
            <th>Supprimer</th>
        </thead>
    <tbody>
<?php
foreach($images as $image) {
?>
        <tr>
            <td><a href="<?= $image->url ?>"><?= htmlspecialchars($image->title) ?></a></td>
            <td><?= $image->user->displayName() ?></td>
            <td><?= $image->rDate('date_sent') ?></td>
            <td><a class="fas fa-trash" href="<?= PATH ?>delete/<?= $image->id ?>/"></a></td>
        </tr>
<?
}
?>
        </tbody>
    </table>
    <?= $pageSelector ?>
</div>
<?php
$content = ob_get_clean();

ob_start();
?>
<script>
    let input = $('#file');
    let info = $('#file-name');
    input.on('change', (e) => {
        let fileName = input.prop('files')[0].name;
        info.text('Fichier: '+fileName);
    });
</script>

<?php
if(!isset($scripts)) {
    $scripts = [];
}
$scripts[] = ob_get_clean();
require('template.php');