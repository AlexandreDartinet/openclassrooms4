<?php
/**
 * Affiche le formulaire d'envoi de mail en cas de mot de passe oublié
 */
ob_start();
?>
<h2>Vous avez oublié votre mot de passe</h2>
<p>Entrez votre nom de compte ou votre adresse mail ci dessous </p>
<?php
if(RETRY != '') { // Si il y a une erreur, on l'affiche
?>
<p>Erreur : <?= RETRY ?></p>
<?php
}
/**
 * Le formulaire envoie en post à / les valeurs suivantes :
 * @var string action : sendRecover (hidden)
 * @var string recover : Nom d'utilisateur ou email ou envoyer le recover (required)
 */
?>
<form method="post" action="/">
    <input type="hidden" name="action" value="sendRecover"/>
    <input type="text" name="recover" placeholder="Nom ou email" required/>
    <input type="submit"/>
</form>
<?php
$content = ob_get_clean();
require('template.php');