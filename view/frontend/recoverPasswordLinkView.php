<?php
/**
 * Gère l'affichage de changement de mot de passe en cas de mot de passe oublié
 */
ob_start();
?>
<h2>Vous avez oublié votre mot de passe <?= $user->name ?></h2>
<p>Entrez votre nouveau mot de passe ci dessous</p>
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

<?php
$scripts = ['<script src="/public/js/passwordVerify.js"></script>'];
$content = ob_get_clean();
require('template.php');