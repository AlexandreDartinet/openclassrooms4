<?php
/**
 * Classe gérant les interactions avec la bdd en rapport avec la table users
 * 
 * @var int USER_PAGE : Nombre d'utilisateurs à afficher par page
 * @var string TABLE_NAME : Nom de la table
 * 
 * @see Manager : classe parente
 */
class UserManager extends Manager {

    const USER_PAGE = 20;
    const TABLE_NAME = 'users';

    /**
     * Fonction permettant l'authentification d'un utilisateur, enregistre l'utilisateur retourné dans la session et efface le recover associé à l'utilisateur s'il existe.
     * Met aussi à jour la dernière date d'activité et l'ip de l'utilisateur.
     * 
     * @param string $name : Nom de l'utilisateur
     * @param string $password : Mot de passe de l'utilisateur en clair
     * 
     * @return boolean : true en cas de succès
     */
    public function login(string $name, string $password) {
        $req = $this->getBy('name', $name);
        if(!is_bool($req)) {
            $line = $req->fetch();
            if(!is_bool($line)) {
                $user = new User($line);
            }
            else {
                return false;
            }
            $req->closeCursor();
            if(password_verify($password, $user->password)) {
                $user->last_seen = User::now();
                $user->ip = $_SERVER['REMOTE_ADDR'];
                $_SESSION['user'] = $user;
                $this->setUser($user);
                $recoverManager = new RecoverManager();
                $recoverManager->removeRecoverByUser($user);
                return true;
            }
        }
        throw new Exception("UserManager: Nom d'utilisateur ou mot de passe incorrect.");
        return false;
    }

    /**
     * Retourne la liste de tous les utilisateurs
     * 
     * @param mixed $page : page à retourner (défaut 1) "all" si on veut récupérer tous les utilisateurs
     * 
     * @return array : Tableau d'objets User représentant la totalité des utilisateurs de la page.
     */
    public function getUsers($page = 1) {
        if(is_int($page)) {
            $req = $this->_db->prepare('SELECT * FROM users LIMIT '.(($page-1)*self::USER_PAGE).','.$page*self::USER_PAGE);
        }
        elseif($page = "all") {
            $req = $this->_db->prepare('SELECT * FROM users');
        }
        else {
            throw new Exception("UserManager: Paramètre \$page($page) invalide.");
            return [];
        }
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
            throw new Exception("UserManager: Aucun utilisateur trouvé.");
            return [];
        }
    }

    /**
     * Retourne l'utilisateur correspontant à l'id fourni
     * 
     * @param int $id : Identifiant de l'utilisateur à retourner
     * 
     * @return User : Utilisateur demandé
     */
    public function getUserById(int $id) {
        $req = $this->getBy('id', $id);
        if(!is_bool($req)) {
            $user = new User($req->fetch());
            $req->closeCursor();
            return $user;
        }
        else {
            throw new Exception("UserManager: Aucun utilisateur correspondant à l'id $id.");
            return false;
        }
    }

    /**
     * Retourne les utilisateurs ou le champ $name correspond à $value
     * 
     * @param string $name : Champ à tester
     * @param mixed $value : Valeur avec laquelle tester le champ
     * 
     * @return array : Tableau d'User dont les critères correspondent à la demande
     */
    public function getUsersBy(string $name, $value) {
        $req = $this->getBy($name, $value);
        if(!is_bool($req)) {
            $users = [];
            while($line = $req->fetch()) {
                $user = new User($line);
                $users[] = $user;
            }
            $req->closeCursor();
            return $users;
        }
        else {
            throw new Exception("UserManager: Aucun utilisateur correspondant au $name $value.");
            return [];
        }
    }

    /**
     * Enregistre un nouvel user, ou en modifie un existant dans la bdd
     * Enregistre si l'id est à 0, sinon modifie.
     * 
     * @param User $user : L'user à mettre à jour, ou enregistrer
     * 
     * @return boolean : true si la requête a été executée avec succès, false sinon.
     */
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

    /**
     * Vérifie s'il existe des lignes dans la table ou le champ $name est égal à $value et ou l'id est différent de $id (0 par défaut)
     * 
     * @param string $name : Nom du champ qu'on veut tester
     * @param mixed $value : Valeur avec laquelle on veut tester le champ
     * @param int $id : Identifiant à exclure du résultat
     * 
     * @return boolean : true si une ou plusieurs lignes existent
     */
    public function exists(string $name, $value, $id = 0) {
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
}