<?php
namespace DartAlex;
/**
 * Gère l'affichage de la liste des signalements d'un commentaire
 */
ob_start();
?>
<h2 class="title is-3">Signalements du commentaire <?= $comment->id ?></h2>
<?= $comment->display(true,false,false,true) ?>
<div class="box content">
    <ul>
        <li><a href="/post/<?= $comment->id_post ?>/">Aller à l'article</a></li>
        <!-- <li><a href="/admin/reports/ban/<?= $comment->id ?>/">Supprimer le commentaire et empêcher de nouveaux commentaires de l'utilisateur.</a></li>
        <li><a href="/admin/reports/permaban/<?= $comment->id ?>/">Supprimer le commentaire et interdire l'accès au site de l'utilisateur.</a></li> -->
    </ul>
    <h3>Signalements :</h3>
<?php
foreach($reports as $report) {
?>
    <div class="report box" id="report-<?= $report->id ?>">
        <p>
            <strong><?= $report->user->displayName() ?></strong> 
            le <?= $report->rDate('date_report') ?> 
            <?= (($_SESSION['user']->level >= User::LEVEL_ADMIN)?"IP($report->ip) ":"") ?>
            <a title="Supprimer" class="fas fa-trash report-remove-link" id="report-remove-link-<?= $report->id ?>" href="<?= PATH ?>delete_report/<?= $report->id ?>/"></a>
        </p>
        <p>
            Type de signalement : <?= $report->displayType() ?>
        </p>
        <p>
            Commentaire :<br/> 
            <?= nl2br(htmlspecialchars($report->content)) ?>
        </p>
    </div>
<?php
}
?>
</div>
<?php
echo $pageSelector;
$content = ob_get_clean();

require('template.php');