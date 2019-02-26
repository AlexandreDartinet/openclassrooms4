<?php
/**
 * Toutes les fonctions relatives à l'affichage et au traitement des données de la partie backend du site
 */

/**
 * Fonctions relatives à l'affichage
 */

/**
 * Affiche la page d'accueil de l'interface d'administration
 * 
 * @return void
 */
function viewAdmin() {
    $title = "Accueil";

    require('view/backend/adminView.php');
}

/**
 * Affiche la liste des commentaires signalés
 * 
 * @param int $page : Page à afficher
 * 
 * @return void
 */
function listReports(int $page) {
    $reportManager = new ReportManager();
    $id_comments = $reportManager->getCommentsId($page);
    if(sizeof($id_comments) > 0) {
        $comments = [];
        $commentManager = new CommentManager();
        foreach($id_comments as $id_comment => $reports_nbr) {
            $comments[] = $commentManager->getCommentById((int) $id_comment); 
        }
        $title = 'Signalements';
        $pageSelector = pageSelector(ceil($reportManager->countComments()/CommentManager::COMMENT_PAGE), $page, PATH);

        require('view/backend/listReportsView.php');
    }
    else {
        header('Location: /admin/retry/no_reports/');
    }
}

/**
 * Affiche la liste des signalements liés à un commentaire
 * 
 * @param int $id : Identifiant du commentaire
 * @param int $page : Page à afficher
 * 
 * @return void
 */
function viewCommentReports(int $id, int $page) {
    $commentManager = new CommentManager();
    if($commentManager->exists('id', $id)) {
        $comment = $commentManager->getCommentById($id);
        $reportManager = new ReportManager();
        if($reportManager->exists('id_comment', $id)) {
            $reports = $reportManager->getReports($id,$page);
            $title = "Signalements - Commentaire $id";
            $pageSelector = pageSelector(ceil($reportManager->count('id_comment', $id)/ReportManager::REPORT_PAGE), $page, PATH);

            require('view/backend/commentReportsView.php');
        }
        else {
            header('Location: /admin/reports/retry/no_comment_report/');
        }
    }
    else {
        header('Location: /admin/reports/retry/unknown_id_comment/');
    }
}

/**
 * Affiche la liste des posts dans l'interface d'administration
 * 
 * @param int $page : Page à afficher
 * 
 * @return void
 */
function listPosts(int $page) {
    $postManager = new PostManager();
    $posts = $postManager->getPosts($page, false);
    $title = 'Articles';
    $pageSelector = pageSelector(ceil($postManager->countPosts(false)/PostManager::POST_PAGE), $page, PATH);
    
    require('view/backend/listPostsView.php');
}

/**
 * Fonctions relatives au traitement des données
 */

/**
 * Supprime un signalement
 * Renvoie à la page du commentaire dont le signalement est issu
 * 
 * @param int $id : Identifiant du signalement
 * 
 * @return void
 */
function deleteReport(int $id) {
    $reportManager = new ReportManager();
    $path = preg_replace('/delete_report\/\d+\//', '', PATH);
    if($reportManager->exists('id', $id)) {
        $report = $reportManager->getReportById($id);
        $report->delete();
        header('Location: '.$path.'success/report_delete/');
    }
    else {
        header('Location: '.$path.'retry/invalid_id_report/');
    }
}

/**
 * Supprime un commentaire
 * Renvoie à la page des signalements
 * 
 * @param int $id : Identifiant du commentaire
 * 
 * @return void
 */
function deleteComment(int $id) {
    $commentManager = new CommentManager();
    $path = preg_replace('/delete\/\d+\//', '', PATH);
    if($commentManager->exists('id', $id)) {
        $comment = $commentManager->getCommentById($id);
        $comment->delete();
        header('Location: /admin/reports/success/deleted_comment/');
    }
    else {
        header('Location: /admin/reports/retry/unknown_id_comment/');
    }
}