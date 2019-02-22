<?php

class RecoverManager extends Manager {

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

    public function removeRecoversByUser(User $user) {
        $req = $this->_db->prepare('DELETE FROM recovers WHERE id_user=?');
        return $req->execute([$user->id]);
    }

    public function removeRecover(Recover $recover) {
        $req = $this->_db->prepare('DELETE FROM recovers WHERE id=?');
        return $req->execute([$recover->id]);
    }
}