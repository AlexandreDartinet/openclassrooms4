<?php
namespace DartAlex;
/**
 * Classe gérant les interactions avec la bdd en rapport avec la table recovers
 * 
 * @var string TABLE_NAME : Nom de la table
 * 
 * @see Manager : classe parente
 */
class RecoverManager extends Manager {

    const TABLE_NAME = 'recovers';
    
    /**
     * Retourne le recover associé à un identifiant
     * 
     * @param int $id : Identifiant du recover à retourner
     * 
     * @return Recover : Recover demandé false si aucun
     */
    public function getRecoverById(int $id) {
        if($req = $this->getBy('id', $id)) {
            if($res = $req->fetch()) {
                $recover = new Recover($res);
            }
            else {
                $recover = false;
            }
            $req->closeCursor();
            return $recover;
        }
        else {
            throw new \Exception("RecoverManager: Erreur de requête getRecoverById($id).");
        }
    }

    /**
     * Retourne le recover associé à une clé
     * 
     * @param string $key : Clé du recover à retourner
     * 
     * @return Recover : Recover demandé, false si aucun résultat
     */
    public function getRecoverByKey(string $key) {
        if($req = $this->getBy('recover_key', $key)) {
            if($res = $req->fetch()) {
                $recover = new Recover($res);
            }
            else {
                $recover = false;
            }
            $req->closeCursor();
            return $recover;
        }
        else {
            throw new \Exception("RecoverManager: Erreur de requête getRecoverByKey($key).");
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
        if($req = $this->getBy('id_user', $user->id)) {
            if($res = $req->fetch()) {
                $recover = new Recover($res);
            }
            else {
                $recover = false;
            }
            $req->closeCursor();
            return $recover;
        }
        else {
            throw new \Exception("RecoverManager: Erreur de requête getRecoverByUser($user->id).");
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
        return $this->removeBy('id_user', $user->id);
    }

    /**
     * Supprime un recover de la bdd
     * 
     * @param Recover $recover : Recover à supprimer
     * 
     * @return boolean : true si succès
     */
    public function removeRecover(Recover $recover) {
        return $this->removeBy('id', $recover->id);
    }
}