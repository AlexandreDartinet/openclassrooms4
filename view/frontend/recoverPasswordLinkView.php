<?php
$title = "Changement de mot de passe pour $user->name";
ob_start();
?>
<h2>Vous avez oublié votre mot de passe <?= $user->name ?></h2>
<p>Entrez votre nouveau mot de passe ci dessous</p>
<?php
if(RETRY != '') {
?>
<p>Erreur : <?= RETRY ?></p>
<?php
}
?>
<form method="post" action="/">
    <input type="hidden" name="action" value="useRecover"/>
    <input type="hidden" name="key" value="<?= $recover->recover_key ?>"/>
    <input type="hidden" name="id_user" value="<?= $user->id ?>"/>
    <div>
        <label for="password">Nouveau mot de passe :</label><br/>
        <input type="password" name="password" id="password" placeholder="Entrez votre nouveau mot de passe" required/>
        <input type="password" name="password_confirm" id="password_confirm" placeholder="Confirmez le mot de passe" required/>
    </div>
    <input type="submit"/>
</form>
<script src="/public/js/passwordVerify.js"></script>
<?php
$content = ob_get_clean();
require('template.php');