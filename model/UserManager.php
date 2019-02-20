<?php

class UserManager extends Manager {

    public function __constructor() {
        parent::__constructor();
    }
    public function login(string $name, string $password) {
        $req = $this->_db->prepare('SELECT * FROM users WHERE name=? AND password=?');
        if($req->execute([$name, password_hash($password)])) {
            $user = new User($req->fetch());
            $req->closeCursor();
            $_SESSION['user'] = $user;
            return true;
        }
        else {
            throw new Exception("Nom d'utilisateur ou mot de passe incorrect.");
            return false;
        }
        
    }

    public function getUsers() {
        $req = $this->_db->prepare('SELECT * FROM users');
        if($req->execute()) {
            $users = [];
            while($user = new User($req->fetch())) {
                $users[] = $user;
            }
            $req->closeCursor();
            return $users;
        }
        else {
            throw new Exception("Aucun utilisateur trouvé.");
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
        }
    }
}