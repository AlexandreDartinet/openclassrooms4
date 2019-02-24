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
        require('controller/backend.php');
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
                default:
                    throw new Exception('$_POST["action"]('.$_POST['action'].') erreur: l\'action n\'existe pas.');
                    break;
            }
        }

        if (preg_match('/\/retry\/\w+\//', PATH)) { // Si il y a une erreur, on crée une variable globale la contenant
            define('RETRY', preg_replace('/^.*\/retry\/(\w+)\/.*$/', '$1', PATH));
        }
        else { // Sinon la variable est vide
            define('RETRY','');
        }
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
            $id = (int) preg_replace('/^\/post\/(\d+)\/.*$/', '$1', PATH);
            $page = getPage(PATH);
            viewPost($id, $page);
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
        elseif (PATH == "/") {
            $page = getPage(PATH);
            listPosts($page);
        }
        else {
            header('Location: /');
        }
    }
}
catch(Exception $e) { // Affichage des erreurs
    echo 'Erreur : ' . $e->getMessage();
}
