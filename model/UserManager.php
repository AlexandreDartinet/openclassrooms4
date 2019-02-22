<?php

class UserManager extends Manager {

    public function login(string $name, string $password) {
        $req = $this->_db->prepare('SELECT * FROM users WHERE name=?');
        if($req->execute([$name])) {
            $user = new User($req->fetch());
            $req->closeCursor();
            if(password_verify($password, $user->password)) {
                $_SESSION['user'] = $user;
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

    public function setUser(User $user) {
        if ($user->id == 0) {
            $req = $this->_db->prepare('INSERT INTO users(name, password, mail, level, ip, name_display) VALUES (?, ?, ?, ?, ?, ?)');
            $exec = $req->execute([
                $user->name,
                $user->password,
                $user->mail,
                $user->level,
                $user->ip,
                $user->name_display
            ]);
            
        }
        else {
            $req = $this->_db->prepare('UPDATE users SET name=?, password=?, mail=?, level=?, ip=?, name_display=?, last_seen=NOW() WHERE id=?');
            $exec = $req->execute([
                $user->name,
                $user->password,
                $user->mail,
                $user->level,
                $user->ip,
                $user->name_display,
                $user->id
            ]);
        }
        $req->closeCursor();
        return $exec;
    }
}