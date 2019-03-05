<?php
namespace DartAlex;
/**
 * Classe gérant les interactions avec la bdd en rapport avec la table reports
 * 
 * @var int REPORT_PAGE : Nombre de signalements à afficher sur une page
 * @var string TABLE_NAME : Nom de la table
 * 
 * @see Manager : classe parente
 */
class ReportManager extends Manager {
    
    const REPORT_PAGE = 20;
    const TABLE_NAME = 'reports';

    /**
     * Retourne tous les repors liés à un commentaire, par page (par défaut page 1)
     * 
     * @param int $id_comment : L'identifiant du post dont on veut les commentaires
     * @param mixed $page : Optionnel, numéro de page de signalements (par défaut 1) ou "all" si on veut tous les signalements
     * 
     * @return array : Tableau d'objets Comment représentant les reports du commentaire, triés par date d'envoi.
     */
    public function getReports(int $id_comment, $page = 1) {
        if(is_int($page)) {
            $req = $this->_db->prepare('SELECT * FROM reports WHERE id_comment=:id_comment ORDER BY date_report ASC LIMIT '.(($page-1)*self::REPORT_PAGE).','.$page*self::REPORT_PAGE);
        }
        elseif($page == "all") {
            $req = $this->_db->prepare('SELECT * FROM reports WHERE id_comment=:id_comment ORDER BY date_report ASC');
        }
        else {
            throw new \Exception("ReportManager: getReports($id_comment, $page): Paramètre \$page($page) invalide.");
        }
        $req->bindParam(":id_comment", $id_comment);
        if($req->execute()) {
            $reports = [];
            while($line = $req->fetch()) {
                $report = new Report($line);
                $reports[] = $report;
            }
            $req->closeCursor();
            return $reports;
        }
        else {
            throw new \Exception("ReportManager: Erreur de requête getReports($id_comment, $page).");
        }
    }

    /**
     * Retourne un tableau d'identifiants de commentaires signalés
     * 
     * @param mixed $page : Optionnel, numéro de page de signalements (par défaut 1) ou "all" si on veut tous les signalements
     * 
     * @return array : Tableau contenant tous les "id" => "reports_nbr" 
     */
    public function getCommentsId($page = 1) {
        if(is_int($page)) {
            $req = $this->_db->prepare('SELECT id_comment, COUNT(*) AS reports_nbr FROM reports GROUP BY id_comment ORDER BY id_comment ASC LIMIT '.(($page-1)*CommentManager::COMMENT_PAGE).','.$page*CommentManager::COMMENT_PAGE);
        }
        elseif($page == "all") {
            $req = $this->_db->prepare('SELECT id_comment, COUNT(*) AS reports_nbr FROM reports GROUP BY id_comment ORDER BY id_comment ASC');
        }
        else {
            throw new \Exception("ReportManager: getCommentsId($page): Paramètre \$page($page) invalide.");
        }
        if($req->execute()) {
            $comments = [];
            while($line = $req->fetch()) {
                $comments[$line['id_comment']] = $line['reports_nbr'];
            }
            $req->closeCursor();
            return $comments;
        }
        else {
            throw new \Exception("ReportManager: getCommentsId($page): Erreur de requête.");
        }
    }
    /**
     * Retourne les reports associés à un User
     * 
     * @param User $user : Utilisateur dont on veut les reports
     * 
     * @return array : Tableau de Report
     */
    public function getReportsByUser(User $user) {
        if($req = $this->getBy('id_user', $user->id)) {
            $reports = [];
            while($line = $req->fetch()) {
                $report = new Report($line);
                $reports[] = $report;
            }
            $req->closeCursor();
            return $reports;
        }
        else {
            throw new \Exception("ReportManager: getReportsByUser($user->id): Erreur de requête.");
        }
    }

    /**
     * Retourne le report associé à un identifiant
     * 
     * @param int $id : Identifiant du report à retourner
     * 
     * @return Report : Recover demandé
     */
    public function getReportById(int $id) {
        if($req = $this->getBy('id', $id)) {
            if($res = $req->fetch()) {
                $report = new Report($res);
            }
            else {
                $report = false;
            }
            $req->closeCursor();
            return $report;
        }
        else {
            throw new \Exception("ReportManager: getReportById($id): Erreur de requête.");
        }
    }

    /**
     * Enregistre un nouveau report, ou en modifie un existant dans la bdd
     * Enregistre si l'id est à 0, sinon modifie.
     * 
     * @param Report $report : Le report à mettre à jour, ou enregistrer
     * 
     * @return boolean : true si la requête a été executée avec succès, false sinon.
     */
    public function setReport(Report $report) {
        if ($report->id == 0) {
            $req = $this->_db->prepare('INSERT INTO reports(id_comment, id_user, ip, `type`, content) VALUES (?, ?, ?, ?, ?)');
            $exec = $req->execute([
                $report->id_comment,
                $report->id_user,
                $report->ip,
                $report->type,
                $report->content
            ]);
        }
        else {
            $req = $this->_db->prepare('UPDATE reports SET id_comment=?, id_user=?, ip=?, date_report=?, `type`=?, content=? WHERE id=?');
            $exec = $req->execute([
                $report->id_comment,
                $report->id_user,
                $report->ip,
                $report->date_report,
                $report->type,
                $report->content,
                $report->id
            ]);
        }
        $req->closeCursor();
        return $exec;
    }

    /**
     * Supprime les reports associé à un commentaire
     * 
     * @param Comment $comment : Comment dont on veut supprimer les reports
     * 
     * @return boolean : true si la requête s'est exécutée avec succès
     */
    public function removeReportsByComment(Comment $comment) {
        return $this->removeBy('id_comment', $comment->id);
    }

    /**
     * Supprime un report de la bdd
     * 
     * @param Report $report : Report à supprimer
     * 
     * @return boolean : true si succès
     */
    public function removeReport(Report $report) {
        return $this->removeBy('id', $report->id);
    }

    /**
     * Retire un utilisateur des reports et le change en anonyme.
     * 
     * @param User $user : Utilisateur qu'on souhaite retirer
     * 
     * @return boolean : true si succès
     */
    public function removeUser(User $user) {
        $req = $this->_db->prepare('UPDATE reports SET id_user=0 WHERE id_user=?');
        return $req->execute([$user->id]);
    }

    /**
     * Compte le nombre de commentaires signalés
     * 
     * @return int : Nombre de commentaires
     */
    public function countComments() {
        $req = $this->_db->prepare('SELECT COUNT(*) as count FROM comments WHERE id IN (SELECT id_comment FROM reports GROUP BY id_comment)');
        if($req->execute()) {
            if($res = $req->fetch()) {
                $count = (int) $res['count'];
            }
            else {
                $count = 0;
            }
            $req->closeCursor();
            return $count;
        }
        else {
            throw new \Exception("ReportManager: countComments(): Erreur de requête.");
        }
    }
}