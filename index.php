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
                    else {
                        throw new Exception('$_POST["action"]('.$_POST['action'].') erreur: des champs sont manquants.');
                    }
                    break;
                case "login":
                    if(isset($_POST['name']) && isset($_POST['password']) && isset($_GET['path'])) {
                        login($_POST['name'], $_POST['password'], $_GET['path']);
                    }
                    else {
                        throw new Exception('$_POST["action"]('.$_POST['action'].') erreur: des champs sont manquants.');
                    }
                    break;
                case "registerUser":
                    if(isset($_POST['name']) && isset($_POST['password']) && isset($_POST['password_confirm']) && isset($_POST['email']) && isset($_POST['email_confirm']) && isset($_POST['name_display'])) {
                        registerUser($_POST['name'], $_POST['password'], $_POST['password_confirm'], $_POST['email'], $_POST['email_confirm'], $_POST['name_display']);
                    }
                    else {
                        throw new Exception('$_POST["action"]('.$_POST['action'].') erreur: des champs sont manquants.');
                    }
                    break;
                case "modifyUser":
                    if(isset($_POST['id']) && isset($_POST['name']) && isset($_POST['email']) && isset($_POST['email_confirm']) && isset($_POST['name_display'])) {
                        if(isset($_POST['password']) && isset($_POST['password_confirm']) && isset($_POST['old_password'])) {
                            $password = $_POST['password'];
                            $password_confirm = $_POST['password_confirm'];
                            $old_password = $_POST['old_password'];
                        }
                        else {
                            $password = '';
                            $old_password = '';
                        }
                        modifyUser((int) $_POST['id'], $_POST['name'], $_POST['name_display'], $_POST['email'], $_POST['email_confirm'], $password, $password_confirm, $old_password);
                    }
                    else {
                        throw new Exception('$_POST["action"]('.$_POST['action'].') erreur: des champs sont manquants.');
                    }
                    break;
                case "sendContactForm":
                    if(isset($_POST['email']) && isset($_POST['email_confirm']) && isset($_POST['name']) && isset($_POST['message'])) {
                        sendContactForm($_POST['email'], $_POST['email_confirm'], $_POST['name'], $_POST['message']);
                    }
                    else {
                        throw new Exception('$_POST["action"]('.$_POST['action'].') erreur: des champs sont manquants.');
                    }
                case "sendRecover":
                    if(isset($_POST['recover'])) {
                        sendRecover($_POST['recover']);
                    }
                    else {
                        throw new Exception('$_POST["action"]('.$_POST['action'].') erreur: des champs sont manquants.');
                    }
                    break;
                case "useRecover":
                    if(isset($_POST['key']) && isset($_POST['id_user']) && isset($_POST['password']) && isset($_POST['password_confirm'])) {
                        useRecover($_POST['key'], (int) $_POST['id_user'], $_POST['password'], $_POST['password_confirm']);
                    }
                    else {
                        throw new Exception('$_POST["action"]('.$_POST['action'].') erreur: des champs sont manquants.');
                    }
                    break;
                default:
                    throw new Exception('$_POST["action"]('.$_POST['action'].') erreur: l\'action n\'existe pas.');
                    break;
            }
        }
        if (preg_match('/^\/logout\//', $_GET['path'])) {
            logout();
        }
        elseif (preg_match('/^\/recover\/$/', $_GET['path'])) {
            recoverPasswordView();
        }
        elseif (preg_match('/^\/recover\/.+\/$/', $_GET['path'])) {
            $key = preg_replace('/^\/recover\/(.+)\/$/', '$1', $_GET['path']);
            recoverPasswordLinkView($key);
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
        elseif (preg_match('/^\/contact\//', $_GET['path'])) {
            viewContactForm();
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
