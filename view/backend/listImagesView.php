<?php
namespace DartAlex;
/**
 * Gère l'affichage de la liste des images et l'envoi de nouvelles
 */
ob_start(); 
?>
<h2>Liste des images</h2>
<form action="<?= PATH ?>" method="post" enctype="multipart/form-data">
    <input type="hidden" name="action" value="addImage"/>
    <div>
        <label for="title">Titre de l'image :</label><br/>
        <input type="text" name="title" id="title" required/>
    </div>
    <div>
        <label for="file">Fichier : </label><br/>
        <input type="file" name="file" id="file"/>
    </div>
    <input type="submit"/>
</form>
<table>
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
<?php
$content = ob_get_clean();
require('template.php');