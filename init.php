<?php

if(file_exists('config.php')) {
    require('config.php');
    if(!CONFIG_SET) {
        die("Veuillez configurer le site en modifiant le fichier config.php.");
    }
}
else {
    $configfile = fopen("config.php", "w") or die("Je n'ai pas les permissions pour créer config.php.");
    $config = 
"<?php
define('DB_HOST','XXX');
define('DB_USER','XXX');
define('DB_PASSWORD','XXX');
define('DB_BASE','XXX');

define('SITE_URL', 'xxx');

define('USE_PHPMAILER', false);
define('SMTP_HOST','XXX');
define('SMTP_USER','XXX');
define('SMTP_PASSWORD','XXX');
define('SMTP_PORT',465);
define('CONTACT_MAIL','XXX');
define('PHPMAILER_PATH', 'model/PHPMailer/');

define('CONFIG_SET', false);
";
    fwrite($configfile, $config);
    fclose($configfile);
    die("Fichier config.php par défaut créé, merci de le modifier pour pouvoir utiliser le site.");
}

function loadClass($name) {
    require "model/$name.php";
}
spl_autoload_register("loadClass");
session_start();
if(!isset($_SESSION["user"])) {
    $_SESSION["user"] = User::default();
}
if(USE_PHPMAILER) {
    try {
        include PHPMAILER_PATH.'src/Exception.php';
        include PHPMAILER_PATH.'src/PHPMailer.php';
        include PHPMAILER_PATH.'src/SMTP.php';  
    }
    catch (Exception $e) {
        echo 'Veuillez installer PHPMailer <a href="https://github.com/PHPMailer/PHPMailer">https://github.com/PHPMailer/PHPMailer</a>.<br/>'.$e->getMessage();
    }
    function smtpMailer($to, $from, $from_name, $subject, $body) {
        $mail = new PHPMailer\PHPMailer\PHPMailer();
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
else {
    function smtpMailer($to, $from, $from_name, $subject, $body) {
        $headers = [
            'From' => $from_name.' <'.$from.'>',
            'Reply-To' => $from_name.' <'.$from.'>',
            'X-Mailer' => 'PHP/'.phpversion()
        ];
        mail($to, $subject, $body, $headers);
    }
}