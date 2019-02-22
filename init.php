<?php


function loadClass($name) {
    require "model/$name.php";
}
spl_autoload_register("loadClass");
session_start();
if(!isset($_SESSION["user"])) {
    $_SESSION["user"] = User::default();
}

try {
    include PHPMAILER_PATH.'src/Exception.php';
    include PHPMAILER_PATH.'src/PHPMailer.php';
    include PHPMAILER_PATH.'src/SMTP.php';  
}
catch (Exception $e) {
    echo 'Veuillez installer PHPMailer <a href="https://github.com/PHPMailer/PHPMailer">https://github.com/PHPMailer/PHPMailer</a>.<br/>'.$e->getMessage();
}
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
function smtpMailer($to, $from, $from_name, $subject, $body) {
    $mail = new PHPMailer();
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
    if(!$mail->Send()) {
        return 'Mail error: '.$mail->ErrorInfo;
    } else {
        return true;
    }
}