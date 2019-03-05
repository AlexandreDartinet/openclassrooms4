<?php
namespace DartAlex;
/**
 * Affiche le formulaire d'envoi de mail en cas de mot de passe oublié
 */
ob_start();
?>
<h2 class="title is-3">Vous avez oublié votre mot de passe</h2>
<div class="box">
<?php
/**
 * Le formulaire envoie en post à / les valeurs suivantes :
 * @var string action : sendRecover (hidden)
 * @var string recover : Nom d'utilisateur ou email ou envoyer le recover (required)
 */
?>
    <form method="post" action="/">
        <input type="hidden" name="action" value="sendRecover"/>
        <label for="recover" class="label">Nom du compte ou adresse email</label>
        <div class="field has-addons">
            <div class="control is-expanded">
                <input class="input" type="text" name="recover" id="recover" placeholder="Nom ou email" required/>
            </div>
            <div class="control">
                <input class="button is-primary" type="submit" value="Envoyer"/>
            </div>
        </div>
    </form>
 </div>
<?php
$content = ob_get_clean();
require('template.php');