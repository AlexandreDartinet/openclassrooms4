<?php
$title = "Mot de passe oublié";
ob_start();
?>
<h2>Vous avez oublié votre mot de passe</h2>
<p>Entrez votre nom de compte ou votre adresse mail ci dessous </p>
<?php
if(RETRY != '') {
?>
<p>Erreur : <?= RETRY ?></p>
<?php
}
?>
<form method="post" action="/">
    <input type="hidden" name="action" value="sendRecover"/>
    <input type="text" name="recover" placeholder="Nom ou email" required/>
    <input type="submit"/>
</form>
<?php
$content = ob_get_clean();
require('template.php');