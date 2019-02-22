<?php


function listPosts(int $page = 1) {
    $postManager = new PostManager();
    $posts = $postManager->getPosts();

    require("view/frontend/listPostsView.php");
}

function viewPost(int $id, $page = 1) {
    $postManager = new PostManager();
    $post = $postManager->getPostById($id);
    if($post->comments_nbr != 0) {
        $commentManager = new CommentManager();
        $comments = $commentManager->getComments($id, $page);
    }

    require("view/frontend/postView.php");
}

function viewRegister() {
    if($_SESSION['user']->id != 0) {
        header('Location: /');
    }
    else {
        require("view/frontend/registerView.php");
    }
}

function viewProfileEdit() {
    if($_SESSION['user']->id == 0) {
        header('Location: /');
    }
    else {
        require("view/frontend/profileEditView.php");
    }
}

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

function login($name, $password, $path) {
    $userManager = new UserManager();
    if($userManager->login($name, $password)) {
        header("Location: $path");
    }
}

function logout() {
    $_SESSION['user'] = User::default();
    header("Location: /");
}

function registerUser($name, $password, $email, $name_display) {
    $userManager = new UserManager();
    if($_SESSION['user']->id != 0) {
        header('Location: /');
    }
    elseif($userManager->exists('name', $name)) {
        header('Location: /register/retry/name/');
    }
    elseif($userManager->exists('name_display', $name_display)) {
        header('Location: /register/retry/name_display/');
    }
    else {
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
}

function modifyUser(int $id, string $name, string $name_display, string $email, string $password, string $old_password) {
    $user = clone $_SESSION['user'];
    $userManager = new UserManager();
    if($id != $user->id) {
        header('Location: /');
    }
    if($old_password != '' && $password != '') {
        if(password_verify($old_password, $user->password)) {
            $user->password = password_hash($password, PASSWORD_DEFAULT);
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
    $user->email = $email;
    $user->ip = $_SERVER['REMOTE_ADDR'];
    $user->last_seen = User::now();
    $userManager->setUser($user);
    $_SESSION['user'] = $user;
    header('Location: /profile/edit/');
}