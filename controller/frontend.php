<?php
/**
 * Toutes les fonctions relatives à l'affichage et au traitement des données de la partie frontend du site
 */

 /**
  * Fonctions relatives à l'affichage
  */

/**
 * Liste les posts
 * 
 * @param int $page : Quelle page on souhaite afficher (par défaut 1)
 * 
 * @return void
 */
function listPosts(int $page = 1) {
    $postManager = new PostManager();
    $posts = $postManager->getPosts($page);
    $pageSelector = pageSelector(ceil($postManager->count()/PostManager::POST_PAGE), $page, PATH);

    require("view/frontend/listPostsView.php");
}

/**
 * Affiche un post et ses commentaires.
 * 
 * @param int $id : Identifiant du post à afficher
 * @param int $page : Quelle page des commentaires on souhaite afficher (par défaut 1)
 * 
 * @return void
 */
function viewPost(int $id, $page = 1) {
    $postManager = new PostManager();
    $post = $postManager->getPostById($id);
    $reply_to = 0;
    $isComments = false;
    $edit = false;
    if($post->comments_nbr != 0) {
        $isComments = true;
        $commentManager = new CommentManager();
        $comments = $commentManager->getComments($id, $page);
        if(preg_match('/\/reply_to\/\d+\//', PATH)) {
            $reply_to = (int) preg_replace('/^.*reply_to\/(\d+)\/.*$/', '$1', PATH);
            $reply_to_comment = $commentManager->getCommentById($reply_to);
        }
        if(preg_match('/\/edit\/\d+\//', PATH)) {
            $edit_id = (int) preg_replace('/^.*edit\/(\d+)\/.*$/', '$1', PATH);
            if($commentManager->exists($edit_id)) {
                $editedComment = $commentManager->getCommentById($edit_id);
                if($_SESSION['user']->id == 0) {
                    if($_SESSION['user']->ip == $editedComment->ip) {
                        $edit = true;
                    }
                }
                else {
                    if($_SESSION['user']->id == $editedComment->id_user) {
                        $edit = true;
                    }
                }
            }

        }
        $pageSelector = pageSelector(ceil($commentManager->count($id)/CommentManager::COMMENT_PAGE), $page, PATH);
    }
    
    require("view/frontend/postView.php");
}

/**
 * Affiche le formulaire d'enregistrement si l'utilisateur n'est pas connecté
 * 
 * @return void
 */
function viewRegister() {
    if($_SESSION['user']->id != 0) {
        header('Location: /');
    }
    else {
        require("view/frontend/registerView.php");
    }
}

/**
 * Affiche le formulaire de modification de profil si l'utilisateur est connecté
 * 
 * @return void
 */
function viewProfileEdit() {
    if($_SESSION['user']->id == 0) {
        header('Location: /');
    }
    else {
        $user = $_SESSION['user'];
        require("view/frontend/profileEditView.php");
    }
}

/**
 * Affiche le formulaire de contact
 * 
 * @return void
 */
function viewContactForm() {
    if($_SESSION['user']->id != 0) {
        $name = $_SESSION['user']->name_display;
        $email = $_SESSION['user']->email;
    }
    else {
        $name = '';
        $email = '';
    }
    
    require('view/frontend/contactFormView.php');
}
/**
 * Affiche le formulaire d'envoi d'un lien de récupération de mot de passe
 * 
 * @return void
 */
function viewRecoverPassword() {
    require('view/frontend/recoverPasswordView.php');
}

/**
 * Affiche le formulaire de modification du mot de passe si la clé et valide et non expirée
 * 
 * @return void
 */
function viewRecoverPasswordLink($key) {
    $recoverManager = new RecoverManager();
    if($recoverManager->exists('recover_key', $key)) {
        $recover = $recoverManager->getRecoverByKey($key);
        if($recover->isValid()) {
            $userManager = new UserManager();
            $user = $userManager->getUserById($recover->id_user);
            require('view/frontend/recoverPasswordLinkView.php');
        }
        else {
            $recoverManager->removeRecover($recover);
            header('Location: /recover/retry/date_sent/');
        }
    }
    else {
        header('Location: /recover/retry/recover_key/');
        return;
    }
}

/**
 * Fonctions relatives au traitement des données
 */

/**
 * Enregistrement d'un nouveau commentaire et renvoi de l'utilisateur sur la page du post qu'il vient de commenter
 * 
 * @param int $id_post : Identifiant du post à commenter
 * @param string $name : Nom de l'auteur renseigné (utile si auteur anonyme)
 * @param string $content : Le corps du commentaire
 * @param int $reply_to : L'identifiant du commentaire auquel on répond (0 si le commentaire n'est pas une réponse)
 * 
 * @return void
 */
function commentPost(int $id_post, string $name, string $content, int $reply_to) {
    $commentManager = new CommentManager();
    $comment = Comment::default();
    $comment->id_post = $id_post;
    $comment->name = $name;
    $comment->content = $content;
    $comment->reply_to = $reply_to;
    $commentManager->setComment($comment);
    header("Location: /post/$id_post/");
}

/**
 * Modifie un commentaire si l'utilisateur actuel a le droit de le faire, et le renvoie à la page appropriée
 * 
 * @param int $id : Identifiant du commentaire à modifier
 * @param string $name : Nouveau nom de l'auteur
 * @param string $content : Nouveau contenu du commentaire
 * 
 * @return void
 */
function modifyComment(int $id, string $name, string $content) {
    $user = $_SESSION['user'];
    $commentManager = new CommentManager();
    if($commentManager->exists($id)) {
        $comment = $commentManager->getCommentById($id);
        if($comment->canEdit($user)) {
            if(($content != $comment->content) || ($name != $comment->getName())) {
                $comment->name = $name;
                $comment->content = $content."\nModifié le ".Comment::rNow();
                $commentManager->setComment($comment);
                header('Location: '.PATH);
            }
            else {
                header('Location: '.PATH."edit/$id/retry/modify_nothing_changed/");
            }
        }
        else {
            header('Location: '.PATH.'retry/modify_invalid_user/');
        }
    }
    else {
        header('Location: '.PATH.'retry/modify_id_comment/');
    }
}

/**
 * Supprime un commentaire si l'utilisateur a le droit de le faire, et le renvoie à la page appropriée
 * 
 * @param int $id : Identifiant du commentaire à supprimer
 * 
 * @return void
 */
function deleteComment(int $id) {
    $user = $_SESSION['user'];
    $commentManager = new CommentManager();
    if($commentManager->exists($id)) {
        $comment = $commentManager->getCommentById($id);
        if($comment->canEdit($user)) {
            $commentManager->removeComment($comment);
            header('Location: /post/'.$comment->id_post.'/');
        }
        else {
            header('Location: /post/'.$comment->id_post.'/retry/delete_invalid_user/');
        }
    }
    else {
        header('Location: /post/'.$comment->id_post.'/retry/delete_id_comment/');
    }
}

/**
 * Connecte un utilisateur à son compte et raffraichit la page sur laquelle il était avant la connexion
 * 
 * @param string $name : Nom de l'utilisateur
 * @param string $password : Mot de passe de l'utilisateur en clair
 * @param string $path : Chemin de la page au moment de la connexion
 * 
 * @return void
 */
function login(string $name, string $password, string $path) {
    $userManager = new UserManager();
    if($userManager->login($name, $password)) {
        header("Location: $path");
    }
}

/**
 * Déconnecte un utilisateur et le renvoie à la page d'accueil du site
 * 
 * @return void
 */
function logout() {
    $_SESSION['user'] = User::default();
    header("Location: /");
}

/**
 * Envoie le contenu du formulaire de contact à l'administrateur, et envoie une confirmation à l'expéditeur
 * 
 * @param string $from : Adresse mail de l'expéditeur
 * @param string $from_confirm : Confirmation de l'adresse mail de l'expéditeur
 * @param string $from_name : Nom de l'expéditeur
 * @param string $message : Contenu du message
 * 
 * @return void
 */
function sendContactForm(string $from, string $from_confirm, string $from_name, string $message) {
    if($from == $from_confirm) {
        $ip = $_SERVER['REMOTE_ADDR'];
        $body = 
"Message envoyé par $from_name <$from> depuis l'IP : $ip.
--------------------------------------------------------

$message

--------------------------------------------------------";
        $bodyConfirm = 
"Votre message a bien été envoyé à ".SITE_URL."
--------------------------------------------------------

$message

--------------------------------------------------------
Une réponse vous sera donnée sous peu.
Ceci est un mail automatique, merci de ne pas y répondre.";
        smtpMailer(CONTACT_MAIL, $from, $from_name, "Message de $from_name <$from> sur le site ".SITE_URL, $body);
        smtpMailer($from, "noreply@".SITE_URL, "noreply", "Confirmation de l'envoi de votre message a ".SITE_URL, $bodyConfirm);
        header('Location: /');
        return;
    }
    else {
        header('Location: /contact/retry/email_confirm/');
        return;
    }
}

/**
 * Enregistre un nouvel utilisateur dans la base de données, le connecte, lui envoie une confirmation par mail et le renvoie à la page d'accueil.
 * Le renvoie au formulaire d'enregistrement avec un message d'erreur en cas de mauvaise saisie/duplication de données uniques.
 * 
 * @param string $name : Nom de l'utilisateur (unique)
 * @param string $password : Mot de passe en clair
 * @param string $password_confirm : Confirmation du mot de passe en clair
 * @param string $email : Email de l'utilisateur
 * @param string $email_confirm : Confirmation de l'email de l'utilisateur
 * @param string $name_display : Nom d'affichage de l'utilisateur
 * 
 * @return void
 */
function registerUser(string $name, string $password, string $password_confirm, string $email, string $email_confirm, string $name_display) {
    $userManager = new UserManager();
    if($_SESSION['user']->id != 0) {
        header('Location: /');
        return;
    }
    if($userManager->exists('name', $name)) {
        header('Location: /register/retry/name/');
        return;
    }
    if($userManager->exists('name_display', $name_display)) {
        header('Location: /register/retry/name_display/');
        return;
    }
    if($password != $password_confirm) {
        header('Location: /register/retry/password_confirm/');
        return;
    }
    if($email != $email_confirm) {
        header('Location: /register/retry/email_confirm/');
        return;
    }
    $_SESSION['user']->name = $name;
    $_SESSION['user']->password = password_hash($password, PASSWORD_DEFAULT);
    $_SESSION['user']->email = $email;
    $_SESSION['user']->date_inscription = User::now();
    $_SESSION['user']->last_seen = User::now();
    $_SESSION['user']->level = User::LEVEL_USER;
    $_SESSION['user']->name_display = $name_display;
    $userManager->setUser($_SESSION['user']);
    $userManager->login($name, $password);
    smtpMailer($email, "noreply@".SITE_URL, "noreply", "Nouveau compte sur ".SITE_URL, "Le compte $name a été créé.\nCeci est un mail automatique, merci de ne pas y répondre.");
    header('Location: /');

}
/**
 * Modifie le profil de l'utilisateur connecté en session et dans la bdd.
 * Renvoie l'utilisateur à la page d'accueil si il n'est pas connecté
 * Renvoie l'utilisateur à la page de profil avec un message d'erreur si une donnée est erronée, sans sinon.
 * 
 * @param int $id : Identifiant d'utilisateur fourni par le formulaire
 * @param string $name : Nom d'utilisateur
 * @param string $name_display : Nom d'affichage
 * @param string $email : Email
 * @param string $email_confirm : Confirmation de l'email
 * @param string $password : Nouveau mot de passe en clair
 * @param string $password_confirm : Confirmation du nouveau mot de passe en clair
 * @param string $old_password : Ancien mot de passe en clair
 * 
 * @return void
 */
function modifyUser(int $id, string $name, string $name_display, string $email, string $email_confirm, string $password, string $password_confirm, string $old_password) {
    $user = clone $_SESSION['user'];
    $userManager = new UserManager();
    if($id != $user->id) {
        header('Location: /');
    }
    if($old_password != '' && $password != '') {
        if(password_verify($old_password, $user->password)) {
            if($password == $password_confirm) {
                $user->password = password_hash($password, PASSWORD_DEFAULT);
            }
            else {
                header('Location: /profile/edit/retry/password_confirm/');
                return;
            }
        }
        else {
            header('Location: /profile/edit/retry/password/');
            return;
        }
    }
    if($name != $user->name) {
        if($userManager->exists('name', $name, $id)) {
            header('Location: /profile/edit/retry/name/');
            return;
        }
        else {
            $user->name = $name;
        }
    }
    if($name_display != $user->name_display) {
        if($userManager->exists('name_display', $name, $id)) {
            header('Location: /profile/edit/retry/name_display/');
            return;
        }
        else {
            $user->name_display = $name_display;
        }
    }
    if($user->email != $email) {
        if($email == $email_confirm) {
            $user->email = $email;
        }
        else {
            header('Location: /profile/edit/retry/email_confirm/');
            return;
        }
    }
    $user->ip = $_SERVER['REMOTE_ADDR'];
    $user->last_seen = User::now();
    $userManager->setUser($user);
    $_SESSION['user'] = $user;
    header('Location: /profile/edit/');
}

/**
 * Envoie un mail de récupération à un utilisateur et enregistre la récupération en bdd.
 * Renvoie l'utilisateur à la page d'accueuil si aucune erreur, sinon le renvoie au formulaire d'envoie avec une erreur
 * 
 * @param string $value : Nom ou email de l'utilisateur à qui envoyer le mail
 * 
 * @return void
 */
function sendRecover(string $value) {
    $userManager = new UserManager();
    if(User::isEmail($value)) {
        if($userManager->exists('email', $value)) {
            $users = $userManager->getUsersBy('email', $value);
        }
        else {
            header('Location: /recover/retry/no_match_email/');
            return;
        }
    }
    else {
        if($userManager->exists('name', $value)) {
            $users = $userManager->getUsersBy('name', $value);
        }
        else {
            header('Location: /recover/retry/no_match_name/');
            return;
        }
    }
    $sent = false;
    foreach($users as &$user) {
        $recoverManager = new RecoverManager();
        if($recoverManager->exists('id_user', $user->id)) {
            $recover = $recoverManager->getRecoverByUser($user);
            if($recover->isValid()) {
                $send = false;
            }
            else {
                $recoverManager->removeRecover($recover);
                $send = true;
            }
        }
        else {
            $send = true;
        }
        if($send) {
            $recover = Recover::default();
            $recover->id_user = $user->id;
            $recover->recover_key = password_hash($user->email, PASSWORD_DEFAULT);
            $recoverManager->setRecover($recover);
            $subject = "Lien de modification du mot de passe ".SITE_URL;
            $body = 
"Une demande de réinitialisation a été soumise pour votre compte $user->name sur le site ".SITE_URL.".
Vous pouvez modifier votre mot de passe à l'adresse https://".SITE_URL."/recover/$recover->recover_key/ .
Ce lien expirera dans ".Recover::HOURS_VALID." heures.
Ceci est un message automatique, merci de ne pas y répondre.";
            smtpMailer($user->email, "noreply@".SITE_URL, "noreply", $subject, $body);
            $sent = true;
        }
    }
    if($sent) {
        header('Location: /');
    }
    else {
        header('Location: /recover/retry/nothing_sent/');
    }
}

/**
 * Change le mot de passe d'un utilisateur et le met en session en cas de succès.
 * Renvoie à l'accueil en cas de succès. 
 * Renvoie à la page appropriée avec un message d'erreur en cas d'échec.
 * 
 * @param string $key : clé du recover
 * @param int $id_user : identifiant renvoyé par le formulaire
 * @param string $password : Le nouveau mot de passe
 * @param string $password_confirm : Confirmation du nouveau mot de passe
 * 
 * @return void
 */
function useRecover(string $key, int $id_user, string $password, string $password_confirm) {
    if($password != $password_confirm) {
        header("Location: /recover/$key/retry/password_confirm/");
        return;
    }
    $recoverManager = new RecoverManager();
    $recover = $recoverManager->getRecoverByKey($key);
    if($recover->id_user != $id_user) {
        header("Location: /recover/$key/retry/id_user/");
        return;
    }
    if(!$recover->isValid()) {
        $recoverManager->removeRecover($recover);
        header("Location: /recover/retry/expired/");
        return;
    }
    $userManager = new UserManager();
    $user = $userManager->getUserById($id_user);
    $user->password = password_hash($password, PASSWORD_DEFAULT);
    $userManager->setUser($user);
    $recoverManager->removeRecover($recover);
    $_SESSION['user'] = $user;
    header('Location: /');
}