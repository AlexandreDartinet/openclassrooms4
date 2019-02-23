<?php
/**
 * Classe gérant les interactions avec la bdd en rapport avec la table recovers
 * 
 * @see Manager : classe parente
 */
class RecoverManager extends Manager {
    
    /**
     * Retourne le recover associé à un identifiant
     * 
     * @param int $id : Identifiant du recover à retourner
     * 
     * @return Recover : Recover demandé
     */
    public function getRecoverById(int $id) {
        $req = $this->_db->prepare('SELECT * FROM recovers WHERE id=?');
        if($req->execute([$id])) {
            $recover = new Recover($req->fetch());
            $req->closeCursor();
            return $recover;
        }
        else {
            throw new Exception("Aucun recover correspondant à l'id $id.");
            return false;
        }
    }

    /**
     * Retourne le recover associé à une clé
     * 
     * @param string $key : Clé du recover à retourner
     * 
     * @return Recover : Recover demandé
     */
    public function getRecoverByKey(string $key) {
        $req = $this->_db->prepare('SELECT * FROM recovers WHERE recover_key=?');
        if($req->execute([$key])) {
            $recover = new Recover($req->fetch());
            $req->closeCursor();
            return $recover;
        }
        else {
            throw new Exception("Aucun recover correspondant à la clé $key.");
            return false;
        }
    }

    /**
     * Retourne le recover associé à un User
     * 
     * @param User $user : L'utilisateur dont on veut récupérer le recover
     * 
     * @return Recover : Recover demandé
     */
    public function getRecoverByUser(User $user) {
        $req = $this->_db->prepare('SELECT * FROM recovers WHERE id_user=?');
        if($req->execute([$user->id])) {
            $recover = new Recover($req->fetch());
            $req->closeCursor();
            return $recover;
        }
        else {
            throw new Exception("Aucun recover correspondant à l'utilisateur $user->name.");
            return false;
        }
    }

    /**
     * Enregistre un nouveau recover, ou en modifie un existant dans la bdd
     * Enregistre si l'id est à 0, sinon modifie.
     * 
     * @param Recover $recover : Le recover à mettre à jour, ou enregistrer
     * 
     * @return boolean : true si la requête a été executée avec succès, false sinon.
     */
    public function setRecover(Recover $recover) {
        if ($recover->id == 0) {
            $req = $this->_db->prepare('INSERT INTO recovers(id_user, recover_key) VALUES (?, ?)');
            $exec = $req->execute([
                $recover->id_user,
                $recover->recover_key
            ]);
            
        }
        else {
            $req = $this->_db->prepare('UPDATE recovers SET id_user=?, recover_key=?, date_sent=? WHERE id=?');
            $exec = $req->execute([
                $recover->id_user,
                $recover->recover_key,
                $recover->date_sent,
                $recover->id
            ]);
        }
        $req->closeCursor();
        return $exec;
    }

    /**
     * Supprime le recover associé à un utilisateur
     * 
     * @param User $user : User dont on veut supprimer le recover
     * 
     * @return boolean : true si la requête s'est exécutée avec succès
     */
    public function removeRecoverByUser(User $user) {
        $req = $this->_db->prepare('DELETE FROM recovers WHERE id_user=?');
        return $req->execute([$user->id]);
    }

    /**
     * Supprime un recover de la bdd
     * 
     * @param Recover $recover : Recover à supprimer
     * 
     * @return boolean : true si succès
     */
    public function removeRecover(Recover $recover) {
        $req = $this->_db->prepare('DELETE FROM recovers WHERE id=?');
        return $req->execute([$recover->id]);
    }

    /**
     * Vérifie s'il existe des lignes dans la table ou le champ $name est égal à $value
     * 
     * @param string $name : Nom du champ qu'on veut tester
     * @param string $value : Valeur avec laquelle on veut tester le champ
     * 
     * @return boolean : true si une ou plusieurs lignes existent
     */
    public function exists(string $name, string $value) {
        $req = $this->_db->prepare("SELECT COUNT(*) AS count FROM recovers WHERE `$name`=?");
        if($req->execute([$value])) {
            $res = $req->fetch();
            $req->closeCursor();
            $count = (int) $res['count'];
            if($count == 0) {
                return false;
            }
            else {
                return true;
            }
        }
        else {
            return false;
        }
    }
}