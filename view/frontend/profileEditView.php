<?php
/**
 * Gère l'affichage du formulaire de modification du profil de l'utilisateur
 */
ob_start();
?>
<h2>Modification de votre profil</h2>
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
<form method="post" action="/" autocomplete="off" id="register-form">
    <input type="hidden" name="action" value="modifyUser"/>
    <input type="hidden" name="id" value="<?= $user->id ?>"/>
    <div>
        <label for="name">Pseudonyme :</label>
        <input type="text" name="name" id="name" placeholder="Entrez votre pseudo ici." value="<?= $user->name ?>" required/>
    </div>
    <div>
        <label for="old_password">Ancien mot de passe :</label>
        <input type="password" name="old_password" id="old_password" placeholder="Entrez votre ancien mot de passe ici."/>
    </div>
    <div>
        <label for="password">Nouveau mot de passe :</label>
        <input type="password" name="password" id="password" placeholder="Mot de passe"/>
        <input type="password" name="password_confirm" id="password_confirm" placeholder="Confirmez le mot de passe"/>
    </div>
    <div>
        <label for="name_display">Nom affiché :</label>
        <input type="text" name="name_display" id="name_display" placeholder="Entrez le nom que vous voulez afficher." value="<?= $user->name_display ?>" required/>
    </div>
    <div>
        <label for="email">Email :</label>
        <input type="email" name="email" id="email" placeholder="Entrez votre email." value="<?= $user->email ?>" required/>
        <input type="email" name="email_confirm" id="email_confirm" placeholder="Confirmez votre email." value="<?= $user->email ?>" required/>
    </div>
    <input type="submit"/>
</form>


<?php
$scripts = [
    '<script src="/public/js/passwordVerify.js"></script>',
    '<script src="/public/js/emailVerify.js"></script>'
];
$content = ob_get_clean();
require('template.php');
