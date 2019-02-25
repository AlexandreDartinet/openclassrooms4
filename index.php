<?php
/**
 * Routeur de toutes les requêtes sur le site qui ne vont pas vers /public
 */
require('init.php'); // On inclut le fichier d'initialisation du site

try { // Gestion des erreurs
    require('controller/common.php'); // Controlleur commun à toutes les sections
    /**
     * Bloc de la section backend
     */
    if(preg_match('/^\/admin\//', PATH)) {
        if($_SESSION['user']->level >= User::LEVEL_MODERATOR) {
            require('controller/backend.php');

            viewAdmin();
        }
        else {
            header('Location: /');
        }
    }
    /**
     * Bloc des requêtes ajax
     */
    elseif(preg_match('/^\/ajax\//', PATH)) {
        require('controller/ajax.php');
    }
    /**
     * Bloc de la section frontend
     */
    else {
        require('controller/frontend.php'); // On inclut le contrôleur frontend
        /**
         * Gestion des différentes actions envoyées en post à la partie frontend
         */
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
                case "modifyComment":
                    if(isset($_POST['id']) && isset($_POST['name']) && isset($_POST['content'])) {
                        modifyComment((int) $_POST['id'], $_POST['name'], $_POST['content']);
                    }
                    else {
                        throw new Exception('$_POST["action"]('.$_POST['action'].') erreur: des champs sont manquants.');
                    }
                case "login":
                    if(isset($_POST['name']) && isset($_POST['password'])) {
                        login($_POST['name'], $_POST['password'], PATH);
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
                        if(isset($_POST['password']) && isset($_POST['password_confirm']) && isset($_POST['old_password'])) { // Si le mot de passe est modifié
                            $password = $_POST['password'];
                            $password_confirm = $_POST['password_confirm'];
                            $old_password = $_POST['old_password'];
                        }
                        else { // Sinon on envoie du rien
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
                case "sendReport":
                    if(isset($_POST['id_comment']) && isset($_POST['type']) && isset($_POST['content'])) {
                        sendReport((int) $_POST['id_comment'], (int) $_POST['type'], $_POST['content']);
                    }
                    else {
                        throw new Exception('$_POST["action"]('.$_POST['action'].') erreur: des champs sont manquants.');
                    }
                default:
                    throw new Exception('$_POST["action"]('.$_POST['action'].') erreur: l\'action n\'existe pas.');
                    break;
            }
        }
        else {
            /**
             * Routage vers les différentes pages en fonction de l'addresse fournie en utilisant le controlleur frontend
             */
            if (preg_match('/^\/logout\//', PATH)) {
                logout();
            }
            elseif (preg_match('/^\/recover\/(retry\/\w+\/)?$/', PATH)) {
                viewRecoverPassword();
            }
            elseif (preg_match('/^\/recover\/.+\/(retry\/\w+\/)?$/', PATH)) {
                /**
                 * On passe la clé fournie dans la barre d'adresse au controlleur
                 */
                $key = preg_replace('/^\/recover\/(.+)\/(retry\/\w+\/)?$/', '$1', PATH); 
                viewRecoverPasswordLink($key);
            }
            elseif (preg_match('/^\/post\/\d+\//', PATH)) {
                if(preg_match('/delete\/\d+\//', PATH)) { // Si on a demandé la suppression d'un commentaire
                    $id_comment = (int) preg_replace('/^.*delete\/(\d+)\/.*$/', '$1', PATH);
                    deleteComment($id_comment);
                }
                elseif(preg_match('/report\/\d+\//', PATH)) {
                    $id_comment = (int) preg_replace('/^.*report\/(\d+)\/.*$/', '$1', PATH);
                    $path = preg_replace('/report\/\d+\//', '', PATH);
                    viewReportForm($id_comment, $path);
                }
                else {
                    $id_post = (int) preg_replace('/^\/post\/(\d+)\/.*$/', '$1', PATH);
                    $page = getPage(PATH);
                    viewPost($id_post, $page);
                }
            }
            elseif (preg_match('/^\/register\//', PATH)) {
                viewRegister();
            }
            elseif (preg_match('/^\/profile\/edit\//', PATH)) {
                viewProfileEdit();
            }
            elseif (preg_match('/^\/contact\//', PATH)) {
                viewContactForm();
            }
            elseif (preg_match('/^\/directory\//', PATH)) {
                $page = getPage(PATH);
                viewDirectory($page);
            }
            elseif (preg_match('/^\/profile\/\d+\//', PATH)) {
                $id = (int) preg_replace('/^\/profile\/(\d+)\/.*$/', '$1', PATH);
                viewProfile($id);
            }
            elseif (preg_match('/^\/archive\//', PATH)) {
                $year = 0;
                $month = 0;
                $day = 0;
                if(preg_match('/^\/archive\/\d{4}\//', PATH)) {
                    $year = (int) preg_replace('/^\/archive\/(\d{4})\/.*$/', '$1', PATH);
                }
                if(preg_match('/^\/archive\/\d{4}\/\d{2}\//', PATH)) {
                    $month = (int) preg_replace('/^\/archive\/\d{4}\/(\d{2})\/.*$/', '$1', PATH);
                }
                if(preg_match('/^\/archive\/\d{4}\/\d{2}\/\d{2}\//', PATH)) {
                    $day = (int) preg_replace('/^\/archive\/\d{4}\/\d{2}\/(\d{2})\/.*$/', '$1', PATH);
                }
                $page = getPage(PATH);
                viewArchive($page, $year, $month, $day);
            }
            elseif (preg_match('/^\/(page-\d+\/)?$/', PATH)) {
                $page = getPage(PATH);
                listPosts($page);
            }
            else {
                header('Location: /');
            }
        }
    }
}
catch(Exception $e) { // Affichage des erreurs
    echo 'Erreur : ' . $e->getMessage();
}
