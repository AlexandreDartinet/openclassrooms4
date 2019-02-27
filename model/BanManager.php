<?php
/**
 * Classe gérant les interactions avec la bdd en rapport avec la table bans
 * 
 * @var string TABLE_NAME : Nom de la table
 * @var int BAN_PAGE : Nombre de bas à afficher par page
 * 
 * @see Manager : classe parente
 */
class BanManager extends Manager {

    const TABLE_NAME = 'bans';
    const BAN_PAGE = 20;
    
    /**
     * Retourne la liste de tous les bans
     * 
     * @param mixed $page : page à retourner (défaut 1) "all" si on veut récupérer tous
     * 
     * @return array : Tableau d'objets Ban représentant la totalité des bans de la page.
     */
    public function getBans($page = 1) {
        if(is_int($page)) {
            $req = $this->_db->prepare('SELECT * FROM bans ORDER BY date_ban DESC LIMIT '.(($page-1)*self::BAN_PAGE).','.$page*self::BAN_PAGE);
        }
        elseif($page = "all") {
            $req = $this->_db->prepare('SELECT * FROM bans ORDER BY date_ban DESC');
        }
        else {
            throw new Exception("BanManager: getBans($page): Paramètre \$page($page) invalide.");
        }
        if($req->execute()) {
            $bans = [];
            while($line = $req->fetch()) {
                $ban = new Ban($line);
                $bans[] = $ban;
            }
            $req->closeCursor();
            return $bans;
        }
        else {
            throw new Exception("BanManager: getBans($page): Erreur de requête.");
        }
    }

    /**
     * Retourne le ban associé à un identifiant
     * 
     * @param int $id : Identifiant du ban à retourner
     * 
     * @return Ban : Ban demandé false si aucun
     */
    public function getBanById(int $id) {
        if($req = $this->getBy('id', $id)) {
            if($res = $req->fetch()) {
                $ban = new Ban($res);
            }
            else {
                $ban = false;
            }
            $req->closeCursor();
            return $ban;
        }
        else {
            throw new Exception("BanManager: Erreur de requête getBanById($id).");
        }
    }

    /**
     * Retourne le ban associé à une îp
     * 
     * @param string $ip : Clé du ban à retourner
     * 
     * @return Ban : Ban demandé, false si aucun résultat
     */
    public function getBanByIp(string $ip) {
        if($req = $this->getBy('ip', $ip)) {
            if($res = $req->fetch()) {
                $ban = new Ban($res);
            }
            else {
                $ban = false;
            }
            $req->closeCursor();
            return $ban;
        }
        else {
            throw new Exception("BanManager: Erreur de requête getBanByIp($ip).");
        }
    }

    /**
     * Enregistre un nouveau ban, ou en modifie un existant dans la bdd
     * Enregistre si l'id est à 0, sinon modifie.
     * 
     * @param Ban $ban : Le ban à mettre à jour, ou enregistrer
     * 
     * @return boolean : true si la requête a été executée avec succès, false sinon.
     */
    public function setBan(Ban $ban) {
        if ($ban->id == 0) {
            $req = $this->_db->prepare('INSERT INTO bans(ip, type, content) VALUES (?, ?, ?)');
            $exec = $req->execute([
                $ban->ip,
                $ban->type,
                $ban->content
            ]);
            
        }
        else {
            $req = $this->_db->prepare('UPDATE bans SET ip=?, type=?, content=? WHERE id=?');
            $exec = $req->execute([
                $ban->ip,
                $ban->type,
                $ban->content,
                $ban->id
            ]);
        }
        $req->closeCursor();
        return $exec;
    }

    /**
     * Supprime un ban de la bdd
     * 
     * @param Ban $ban : Ban à supprimer
     * 
     * @return boolean : true si succès
     */
    public function removeBan(Ban $ban) {
        return $this->removeBy('id', $ban->id);
    }
}