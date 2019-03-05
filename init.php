<?php
namespace DartAlex;
/**
 * Fichier init.php, initialise les valeurs, fonctions, fichiers nécessaires à l'ensemble du site.
 */



/**
 * Initialisation relative au fichier de configuration
 */
if(file_exists('config.php')) { // On vérifie si le fichier de configuration existe
    require('config.php');
    if(!CONFIG_SET) { // On vérifie que le fichier de configuration a bien été lu jusqu'au bout, sinon message d'erreur
        die("Veuillez configurer le site en modifiant le fichier config.php.");
    }
}
else {
    $configfile = fopen("config.php", "w") or die("Je n'ai pas les permissions pour créer config.php.");
    $config = 
"<?php
/**
 * Configuration de la base de données
 */
define('DB_HOST','XXX'); // Hote de la base de données
define('DB_USER','XXX'); // Utilisateur pour la connexion à la bdd
define('DB_PASSWORD','XXX'); // Mot de passe de connexion à la bdd
define('DB_BASE','XXX'); // Base qu'on utilise pour le site

/**
 * Configuration de constantes relatives au site
 */
define('SITE_URL', 'xxx'); // Adresse du site
define('CONTACT_MAIL','XXX'); // Mail de contact de l'administrateur

/**
 * Configuration de PHPMailer
 */
define('USE_PHPMAILER', false); // true pour utiliser PHPMailer (à installer séparément : https://github.com/PHPMailer/PHPMailer )
define('SMTP_HOST','XXX'); // L'addresse du serveur smtp
define('SMTP_USER','XXX'); // Le nom d'utilisateur pour la connexion au serveur smtp
define('SMTP_PASSWORD','XXX'); // Le mot de passe pour la connexion au serveur smtp
define('SMTP_PORT',465); // Le port de connexion au serveur smtp
define('PHPMAILER_PATH', 'model/PHPMailer/'); // L'endroit ou est installé PHPMailer

/**
 * Configuration de TinyMCE
 */
define('TINYMCE_KEY', 'XXX'); // Clé de l'api TinyMce

/**
 * Si vous avez tout lu et configuré correctement, passez CONFIG_SET à true.
 */
define('CONFIG_SET', false);
";
    fwrite($configfile, $config);
    fclose($configfile);
    die("Fichier config.php par défaut créé, merci de le modifier pour pouvoir utiliser le site.");
}


/**
 * On fait en sorte que nos classes se chargent automatiquement sans avoir besoin de les require à chaque fois.
 */
function loadClass($name) {
    $name = preg_replace('/DartAlex\\\/', '', $name);
    require "model/$name.php";
}
spl_autoload_register("DartAlex\\loadClass");

/**
 * Interdire l'accès si l'utilisateur est banni
 */
$banManager = new BanManager();
if($ban = $banManager->getBanByIp($_SERVER['REMOTE_ADDR'])) {
    if($ban->type == Ban::TYPE_ALL) {
        die("Vous n'avez pas le droit d'accéder à ce site.");
    }
}

/**
 * Initialisation de la base de données si nécessaire.
 */
$userManager = new UserManager();
if($userManager->count() == 0) { // Si aucun utilisateur n'existe, on crée un nouvel utilisateur admin avec le mot de passe 123456
    $user = new User([
        "id" => 0,
        "name" => "admin",
        "password" => password_hash("123456", PASSWORD_DEFAULT),
        "email" => CONTACT_MAIL,
        "date_inscription" => User::now(),
        "last_seen" => User::now(),
        "level" => User::LEVEL_ADMIN,
        "ip" => $_SERVER["REMOTE_ADDR"],
        "name_display" => "Administrateur"
    ]);
    $user->save();
}

/**
 * Initialisation de la fonction d'envois de mails du site
 */
if(USE_PHPMAILER) { // Si on a décidé d'utiliser PHPMailer
    try { // On essaye de charger les classes nécessaires
        include PHPMAILER_PATH.'src/Exception.php';
        include PHPMAILER_PATH.'src/PHPMailer.php';
        include PHPMAILER_PATH.'src/SMTP.php';  
    }
    catch (\Exception $e) { // Si elles ne sont pas présentes, on signale à l'utilisateur qu'il doit installer PHPMailer ou modifier la config
        echo 'Veuillez installer PHPMailer ou renseigner le chemin d\'accès correct à son répertoire d\'installation. <a href="https://github.com/PHPMailer/PHPMailer">https://github.com/PHPMailer/PHPMailer</a>.<br/>'.$e->getMessage();
    }
    /**
     * Envoie les mails via PHPMailer.
     * @param string $to : Adresse mail du destinataire
     * @param string $from : Adresse mail de l'expéditeur
     * @param string $from_name : Nom de l'expéditeur
     * @param string $subject : Sujet de l'email
     * @param string $body : Corps du mail
     * 
     * Si il n'y a pas d'erreur :
     * @return boolean : true
     * 
     * Si il y a une erreur:
     * @return string : Message d'erreur
     */
    function smtpMailer($to, $from, $from_name, $subject, $body) {
        $mail = new \PHPMailer\PHPMailer\PHPMailer();
        $mail->IsSMTP();
        $mail->SMTPDebug = 0;
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'ssl';
        $mail->Host = SMTP_HOST;
        $mail->Port = SMTP_PORT;
        $mail->Username = SMTP_USER;
        $mail->Password = SMTP_PASSWORD;
        $mail->From = $from;
        $mail->FromName = $from_name;
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->AddAddress($to);
        $mail->addReplyTo($from,$from_name);
        if(!$mail->Send()) {
            return 'Mail error: '.$mail->ErrorInfo;
        } else {
            return true;
        }
    }
}
else { // Si on n'utilise pas PHPMailer
    /**
     * Envoie les mails via mail().
     * @param string $to : Adresse mail du destinataire
     * @param string $from : Adresse mail de l'expéditeur
     * @param string $from_name : Nom de l'expéditeur
     * @param string $subject : Sujet de l'email
     * @param string $body : Corps du mail
     * 
     * Si il n'y a pas d'erreur :
     * @return boolean : true
     * 
     * Si il y a une erreur:
     * @return string : Message d'erreur
     */
    function smtpMailer($to, $from, $from_name, $subject, $body) {
        $headers = [
            'From' => $from_name.' <'.$from.'>',
            'Reply-To' => $from_name.' <'.$from.'>',
            'X-Mailer' => 'PHP/'.phpversion()
        ];
        try {
            mail($to, $subject, $body, $headers);
            return true;
        }
        catch(\Exception $e) {
            return 'Mail error: '.$e->getMessage();
        }
    }
}

/**
 * On initialise la session et ses variables nécessaires au fonctionnement du site
 */
session_start();
if(!isset($_SESSION["user"])) { // Si aucun utilisateur enregistré en session, on enregistre l'utilisateur par défaut.
    $_SESSION["user"] = User::default();
}
elseif($_SESSION['user']->id != 0) {
    $userManager = new UserManager();
    if($user = $userManager->getUserById($_SESSION['user']->id)) {
        $_SESSION['user'] = $user;
    }
    else {
        $_SESSION['user'] = User::default();
    }
}
if (preg_match('/\/retry\/\w+\//', $_GET['path'])) { // Si il y a une erreur, on crée une variable globale la contenant
    define('RETRY', preg_replace('/^.*\/retry\/(\w+)\/.*$/', '$1', $_GET['path']));
    define('RETRY_TABLE', [
        "default" => "Erreur inconnue.",
        "recoverPassword_date_sent" => "Le lien de récupération a expiré.",
        "recoverPassword_key" => "La clé de récupération est invalide.",
        "modifyComment_nothing_changed" => "Le commentaire n'a pas été modifié.",
        "modifyComment_invalid_user" => "Vous n'avez pas le droit de modifier ce commentaire.",
        "modifyComment_id_comment" => "Le commentaire que vous essayez de modifier n'existe pas.",
        "deleteComment_invalid_user" => "Vous n'avez pas le droit de supprimer ce commentaire.",
        "deleteComment_id_comment" => "Le commentaire que vous essayez de supprimer n'existe pas.",
        "login_fail" => "Nom d'utilisateur ou mot de passe incorrect.",
        "email_confirm" => "Email et confirmation ne correspondent pas.",
        "user_name" => "Le nom que vous avez choisi existe déjà.",
        "user_name_display" => "Le nom d'affichage que vous avez choisi existe déjà.",
        "password_confirm" => "Le mot de passe ne correspond pas à la confirmation.",
        "old_password" => "Votre ancien mot de passe est incorrect.",
        "no_match_email" => "Aucun utilisateur correspondant à cet email.",
        "no_match_name" => "Aucun utilisateur correspondant à ce nom.",
        "nothing_sent" => "Aucun email de récupération n'a été envoyé.",
        "recover_id_user" => "Erreur dans le formulaire, les identifiants d'utilisateurs ne correspondent pas.",
        "unknown_id_comment" => "Le commentaire n'existe pas.",
        "missing_fields" => "Il manque des champs du formulaire.",
        "unknown_action" => "L'action demandée n'existe pas.",
        "no_reports" => "Aucun signalement.",
        "no_comment_report" => "Aucun signalement pour ce commentaire.",
        "invalid_id_report" => "Le signalement que vous essayez de supprimer n'existe pas.",
        "no_access" => "Vous n'avez pas accès à cette page.",
        "id_post" => "L'article n'existe pas.",
        "no_auth" => "Vous n'avez pas le droit d'effectuer cette action.",
        "unknown_id_user" => "L'utilisateur n'existe pas.",
        "invalid_ip" => "L'ip est invalide.",
        "exists_ip" => "L'ip est déjà bannie.",
        "unknown_id_ban" => "Le ban n'existe pas."
    ]);
}
else { // Sinon la variable est vide
    define('RETRY','');
}
if (preg_match('/\/success\/\w+\//', $_GET['path'])) { // Si il y a un message, on crée une variable globale le contenant
    define('SUCCESS', preg_replace('/^.*\/success\/(\w+)\/.*$/', '$1', $_GET['path']));
    define('SUCCESS_TABLE', [
        "default" => "Succes inconnu",
        "report_sent" => "Signalement envoyé.",
        "recover_used" => "Votre nouveau mot de passe a été enregistré.",
        "recover_sent" => "Un email de récupération a été envoyé.",
        "profile_updated" => "Votre profil a été mis à jour.",
        "user_register" => "Vous vous êtes inscrit avec succès.",
        "user_level_modified" => "Le niveau de l'utilisateur a été changé.",
        "user_deleted" => "L'utilisateur a été supprimé.",
        "contact_form" => "Votre message a bien été envoyé.",
        "logout" => "Au revoir ;)",
        "login" => "Bienvenue !",
        "deleted_comment" => "Commentaire supprimé.",
        "modified_comment" => "Commentaire modifié.",
        "added_comment" => "Commentaire ajouté.",
        "report_delete" => "Signalement supprimé.",
        "post_published" => "Article publié.",
        "post_unpublished" => "Article masqué.",
        "post_deleted" => "Article supprimé.",
        "post_modified" => "Article modifié.",
        "post_added" => "Article ajouté.",
        "ban_added" => "Ban ajouté.",
        "ban_modified" => "Ban modifié.",
        "ban_deleted" => "Ban supprimé."
    ]);
}
else { // Sinon la variable est vide
    define('SUCCESS','');
}

$path = preg_replace('/retry\/\w+\//', '', $_GET['path']);
define('PATH', preg_replace('/success\/\w+\//', '', $path)); // On définit le path actuel, moins l'erreur ou succes s'il y en a
