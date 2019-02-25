<?php
/**
 * Affichage de la page d'accueil de l'interface d'administration
 */
ob_start();
?>
<h2>Accueil de l'interface d'administration</h2>
<p>Bienvenue <?= htmlspecialchars($_SESSION['user']->name_display) ?>, vous êtes <?= $_SESSION['user']->displayLevel() ?>.</p>
<div>
    <h3><a href="/admin/reports">Signalements</a></h3>
<?php
$reportManager = new ReportManager();
$reports_nbr = $reportManager->count();
$anon_reports_nbr = $reportManager->count('id_user', 0);
$commentManager = new CommentManager();
$comments_nbr = $commentManager->count();
$anon_comments_nbr = $commentManager->count('id_user', 0);
?>
    <p>Il y a <?= $reports_nbr ?> signalement<?= ($reports_nbr == 1)?'':'s' ?> en attente de traitement, <?= $anon_reports_nbr ?> venant <?= ($anon_reports_nbr == 1)?"d'un anonyme":"d'anonymes" ?>.</p>
    <p><?= $comments_nbr ?> commentaire<?= ($comments_nbr == 1)?'':'s' ?>, <?= $anon_comments_nbr ?> venant <?= ($anon_comments_nbr == 1)?"d'un anonyme":"d'anonymes" ?>.</p>
</div>
<?php
if($_SESSION['user']->level >= User::LEVEL_EDITOR) {
?>
<div>
    <h3><a href="/admin/posts/">Articles</a></h3>
<?php
$postManager = new PostManager();
$total = $postManager->count();
$all_published = $postManager->countPosts();
$published = $postManager->count('published', true);
$planned = $all_published - $published;
$waiting = $total - $published;
?>
    <p><?= $all_published ?> articles publié<?= ($all_published == 1)?'':'s' ?>, <?= $planned ?> planifié<?= ($planned == 1)?'':'s' ?>, <?= $waiting ?> en attente de validation.</p>
</div>
<?php
}
if($_SESSION['user']->level >= User::LEVEL_ADMIN) {
?>
<div>
    <h3><a href="/admin/users/">Utilisateurs</a></h3>
<?php
$userManager = new UserManager();
foreach(User::LEVELS as $level => $display) {
    $count = $userManager->count('level', (int) $level);
?>
    <p>Il y a <?= $count ?> <?= $display ?><?= ($count == 1)?' enregistré':'s enregistrés' ?>.</p>
<?php
}
?>
</div>
<div>
    <h3><a href="/admin/bans/">Bannissements</a></h3>
</div>
<?php
}

$content = ob_get_clean();
require('template.php');