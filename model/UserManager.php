<?php

class UserManager extends Manager {

    public function login(string $name, string $password) {
        $req = $this->_db->prepare('SELECT * FROM users WHERE name=?');
        if($req->execute([$name])) {
            $user = new User($req->fetch());
            $req->closeCursor();
            if(password_verify($password, $user->password)) {
                $user->last_seen = User::now();
                $user->ip = $_SERVER['REMOTE_ADDR'];
                $_SESSION['user'] = $user;
                $this->setUser($user);
                $recoverManager = new RecoverManager();
                $recoverManager->removeRecoversByUser($user);
                return true;
            }
        }
        throw new Exception("Nom d'utilisateur ou mot de passe incorrect.");
        return false;
    }

    public function getUsers() {
        $req = $this->_db->prepare('SELECT * FROM users');
        if($req->execute()) {
            $users = [];
            while($line = $req->fetch()) {
                $user = new User($line);
                $users[] = $user;
            }
            $req->closeCursor();
            return $users;
        }
        else {
            throw new Exception("Aucun utilisateur trouvé.");
            return [];
        }
    }

    public function getUserById(int $id) {
        $req = $this->_db->prepare('SELECT * FROM users WHERE id=?');
        if($req->execute([$id])) {
            $user = new User($req->fetch());
            $req->closeCursor();
            return $user;
        }
        else {
            throw new Exception("Aucun utilisateur correspondant à l'id $id.");
            return false;
        }
    }

    public function getUsersBy(string $name, $value) {
        $req = $this->_db->prepare("SELECT * FROM users WHERE `$name`=?");
        if($req->execute([$value])) {
            $users = [];
            while($line = $req->fetch()) {
                $user = new User($line);
                $users[] = $user;
            }
            $req->closeCursor();
            return $users;
        }
        else {
            throw new Exception("Aucun utilisateur correspondant au $name $value.");
            return false;
        }
    }

    public function setUser(User $user) {
        if ($user->id == 0) {
            $req = $this->_db->prepare('INSERT INTO users(name, password, email, level, ip, name_display) VALUES (?, ?, ?, ?, ?, ?)');
            $exec = $req->execute([
                $user->name,
                $user->password,
                $user->email,
                $user->level,
                $user->ip,
                $user->name_display
            ]);
            
        }
        else {
            $req = $this->_db->prepare('UPDATE users SET name=?, password=?, email=?, level=?, ip=?, name_display=?, last_seen=NOW() WHERE id=?');
            $exec = $req->execute([
                $user->name,
                $user->password,
                $user->email,
                $user->level,
                $user->ip,
                $user->name_display,
                $user->id
            ]);
        }
        $req->closeCursor();
        return $exec;
    }

    public function exists(string $name, string $value, $id = 0) {
        $id = (int) $id;
        $req = $this->_db->prepare("SELECT COUNT(*) AS count FROM users WHERE `$name`=? AND id!=?");
        if($req->execute([$value, $id])) {
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

    public function countUsers() {
        $req = $this->_db->prepare("SELECT COUNT(*) AS count FROM users");
        if($req->execute()) {
            $res = $req->fetch();
            $req->closeCursor();
            return (int) $res['count'];
        }
        else {
            return 0;
        }
    }
}