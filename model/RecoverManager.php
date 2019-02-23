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

    public function removeRecoversByUser(User $user) {
        $req = $this->_db->prepare('DELETE FROM recovers WHERE id_user=?');
        return $req->execute([$user->id]);
    }

    public function removeRecover(Recover $recover) {
        $req = $this->_db->prepare('DELETE FROM recovers WHERE id=?');
        return $req->execute([$recover->id]);
    }

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