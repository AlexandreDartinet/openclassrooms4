<?php
namespace DartAlex;
/**
 * Gère l'affichage de changement de mot de passe en cas de mot de passe oublié
 */
ob_start();
?>
<h2 class="title is-3">Vous avez oublié votre mot de passe <?= $user->name ?></h2>
<?php
/**
 * Le formulaire renvoie en post à / les valeurs suivantes :
 * @var string action : useRecover (hidden)
 * @var string key : La clé du recover (hidden)
 * @var string id_user : L'identifiant de l'utilisateur associé au recover (hidden)
 * @var string password : Le nouveau mot de passe (required)
 * @var string old_password : Confirmation du nouveau mot de passe (requires)
 */
?>
<div class="box">
<form method="post" action="/">
    <input type="hidden" name="action" value="useRecover"/>
    <input type="hidden" name="key" value="<?= $recover->recover_key ?>"/>
    <input type="hidden" name="id_user" value="<?= $user->id ?>"/>
    <div class="field">
        <label for="password" class="label">Nouveau mot de passe</label>
        <div class="field-body">
            <div class="field">
                <div class="control is-expanded">
                    <input class="input" type="password" name="password" id="password" placeholder="Entrez votre nouveau mot de passe" required/>
                </div>
            </div>
            <div class="field">
                <div class="control is-expanded">
                    <input class="input" type="password" name="password_confirm" id="password_confirm" placeholder="Confirmez le mot de passe" required/>
                </div>
            </div>
        </div>
    </div>
    <div class="field">
        <div class="control">
            <input class="button is-primary" type="submit" value="Enregistrer"/>
 
        </div>
    </div>
</form></div>

<?php
$scripts = ['<script src="/public/js/passwordVerify.js"></script>'];
$content = ob_get_clean();
require('template.php');