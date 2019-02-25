<?php
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
     * @param mixed $page : Optionnel, numéro de page de commentaires (par défaut 1) ou "all" si on veut tous les commentaires
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
            throw new Exception("ReportManager: Paramètre \$page($page) invalide.");
            return [];
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
            throw new Exception("ReportManager: Aucun signalement trouvé \$id_comment($id_comment), \$page($page).");
            return [];
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
        $req = $this->getBy('id_user', $user->id);
        if(!is_bool($req)) {
            $reports = [];
            while($line = $req->fetch()) {
                $report = new Report($line);
                $reports[] = $report;
            }
            $req->closeCursor();
            return $reports;
        }
        else {
            throw new Exception("ReportManager: L'utilisateur $user->id n'a aucun signalement.");
            return [];
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
        $req = $this->getBy('id', $id);
        if(!is_bool($req)) {
            $report = new Recover($req->fetch());
            $req->closeCursor();
            return $report;
        }
        else {
            throw new Exception("ReportManager: Aucun report correspondant à l'id $id.");
            return false;
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
        return $this->removeBy('id_comment', $comment->id_comment);
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
}