<?php
require('config.php');
require('init.php');
require('controller/frontend.php');

try {
    if(isset($_POST['action'])) {
        switch($_POST['action']) {
            case "commentPost":
                if(isset($_POST['id_post']) && isset($_POST['name']) && isset($_POST['content'])) {
                    commentPost((int) $_POST['id_post'], (string) $_POST['name'], (string) $_POST['content'], (int) $_POST['reply_to']);
                }
                break;
            case "login":
                if(isset($_POST['name']) && isset($_POST['password']) && isset($_GET['path'])) {
                    login($_POST['name'], $_POST['password'], $_GET['path']);
                }
                break;
        }
    }
    if (preg_match('/\/logout\//', $_GET['path'])) {
        logout();
    }
    if (preg_match('/^\/post\//', $_GET['path'])) {
        $id = (int) preg_replace('/^\/post\/(\d+)\//', '$1', $_GET['path']);
        viewPost($id);
    }
    else {
        listPosts();
    }
}
catch(Exception $e) {
    echo 'Erreur : ' . $e->getMessage();
}
