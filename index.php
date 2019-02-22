<?php
require('init.php');

try {
    if(preg_match('/^\/admin\//', $_GET['path'])) {
        require('controller/backend.php');
    }
    else {
        require('controller/frontend.php');
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
                case "registerUser":
                    if(isset($_POST['name']) && isset($_POST['password']) && isset($_POST['email']) && isset($_POST['name_display'])) {
                        registerUser($_POST['name'], $_POST['password'], $_POST['email'], $_POST['name_display']);
                    }
                    break;
                case "modifyUser":
                    if(isset($_POST['id']) && isset($_POST['name']) && isset($_POST['email']) && isset($_POST['name_display'])) {
                        if(isset($_POST['password']) && isset($_POST['old_password'])) {
                            $password = $_POST['password'];
                            $old_password = $_POST['old_password'];
                        }
                        else {
                            $password = '';
                            $old_password = '';
                        }
                        modifyUser((int) $_POST['id'], $_POST['name'], $_POST['name_display'], $_POST['email'], $password, $old_password);
                    }
                    break;
            }
        }
        if (preg_match('/^\/logout\//', $_GET['path'])) {
            logout();
        }
        elseif (preg_match('/^\/post\/\d+\//', $_GET['path'])) {
            $id = (int) preg_replace('/^\/post\/(\d+)\/.*$/', '$1', $_GET['path']);
            viewPost($id);
        }
        elseif (preg_match('/^\/register\//', $_GET['path'])) {
            viewRegister();
        }
        elseif (preg_match('/^\/profile\/edit\//', $_GET['path'])) {
            viewProfileEdit();
        }

        elseif ($_GET['path'] == "/") {
            listPosts();
        }
        else {
            header('Location: /');
        }
    }
}
catch(Exception $e) {
    echo 'Erreur : ' . $e->getMessage();
}
