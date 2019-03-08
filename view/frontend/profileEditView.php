<?php
namespace DartAlex;
/**
 * Gère l'affichage du formulaire de modification du profil de l'utilisateur
 */
ob_start();
?>
<h2 class="title is-3">Modification de votre profil</h2>
<?php
/**
 * Le formulaire renvoie en post à / les valeurs suivantes :
 * @var string action : modifyUser (hidden)
 * @var string id : Identifiant de l'utilisateur (hidden)
 * @var string name : Nom de l'utilisateur (required)
 * @var string old_password : Ancien mot de passe de l'utilisateur
 * @var string password : Nouveau mot de passe
 * @var string password_confirm : Confirmation du mot de passe
 * @var string name_display : Le nom à afficher (required)
 * @var string email : L'adresse email (required)
 * @var string email_confirm : Confirmation de l'adresse email (required)
 */
?>
<div class="box container"><form method="post" action="/" autocomplete="off" id="register-form">
    <input type="hidden" name="action" value="modifyUser"/>
    <input type="hidden" name="id" value="<?= $user->id ?>"/>
    <div class="field is-horizontal">
        <div class="field-label is-normal">
            <label for="name" class="label">Pseudonyme</label>
        </div>
        <div class="field-body">
            <div class="field">
                <div class="control">
                    <input class="input" type="text" name="name" id="name" placeholder="Entrez votre pseudo ici." value="<?= $user->name ?>" required/>
                </div>
            </div>
        </div>
    </div>
    <div class="field is-horizontal">
        <div class="field-label is-normal">
            <label for="old_password" class="label">Ancien mot de passe</label>
        </div>
        <div class="field-body">
            <div class="field">
                <div class="control">
                    <input class="input" type="password" name="old_password" id="old_password" placeholder="Entrez votre ancien mot de passe ici."/>
                </div>
            </div>
        </div>
    </div>
    <div class="field is-horizontal">
        <div class="field-label is-normal">
            <label for="password" class="label">Nouveau mot de passe</label>
        </div>
        <div class="field-body">
            <div class="field">
                <div class="control is-expanded">
                    <input class="input" type="password" name="password" id="password" placeholder="Mot de passe"/>
                </div>
            </div>
            <div class="field">
                <div class="control is-expanded">
                    <input class="input" type="password" name="password_confirm" id="password_confirm" placeholder="Confirmez le mot de passe"/>
                </div>
            </div>
        </div>
    </div>
    <div class="field is-horizontal">
        <div class="field-label is-normal">
            <label for="name_display" class="label">Nom affiché</label>
        </div>
        <div class="field-body">
            <div class="field">
                <div class="control">
                    <input class="input" type="text" name="name_display" id="name_display" placeholder="Entrez le nom que vous voulez afficher." value="<?= $user->name_display ?>" required/>
                </div> 
            </div>
        </div>
    </div>
    <div class="field is-horizontal">
        <div class="field-label is-normal">
            <label for="email" class="label">Email</label>
        </div>
        <div class="field-body">
            <div class="field">
                <div class="control is-expanded">
                    <input class="input" type="email" name="email" id="email" placeholder="Entrez votre email." value="<?= $user->email ?>" required/>
                </div>
            </div>
            <div class="field">
                <div class="control is-expanded">
                    <input class="input" type="email" name="email_confirm" id="email_confirm" placeholder="Confirmez votre email." value="<?= $user->email ?>" required/>
                </div>
            </div>
        </div>
    </div>
    <div class="field is-grouped is-grouped-centered">
        <input class="button is-primary" type="submit"/>
    </div>
</form></div>


<?php
$scripts = [
    '<script src="/public/js/passwordVerify.js"></script>',
    '<script src="/public/js/emailVerify.js"></script>'
];
$content = ob_get_clean();
require('template.php');
