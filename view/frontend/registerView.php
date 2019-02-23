<?php
/**
 * Gère l'affichage du formulaire d'inscription
 */
$title = "Inscription";
ob_start();
?>
<h2>Formulaire d'inscription :</h2>
<?php
if(RETRY != '') { // Si il y a une erreur, on l'affiche
?>
<p>Le <?= RETRY ?> que vous avez choisi est déjà utilisé.</p>
<?php
}
/**
 * Le formulaire envoie à / en post les valeurs suivantes :
 * @var string action : registerUser (hidden)
 * @var string name : Nom de l'utilisateur (required)
 * @var string password : Mot de passe (required)
 * @var string password_confirm : Confirmation du mot de passe (required)
 * @var string name_display : Nom d'affichage (required)
 * @var string email : Email (required)
 * @var string email_confirm : Confirmation de l'email (required)
 */
?>
<form method="post" action="/">
    <input type="hidden" name="action" value="registerUser" required/>
    <div>
        <label for="name">Pseudonyme :</label>
        <input type="text" name="name" id="name" placeholder="Entrez votre pseudo ici." required/>
    </div>
    <div>
        <label for="password">Mot de passe :</label>
        <input type="password" name="password" id="password" placeholder="Mot de passe" required/>
        <input type="password" name="password_confirm" id="password_confirm" placeholder="Confirmez le mot de passe" required/>
    </div>
    <div>
        <label for="name_display">Nom affiché :</label>
        <input type="text" name="name_display" id="name_display" placeholder="Entrez le nom que vous voulez afficher." required/>
    </div>
    <div>
        <label for="email">Email :</label>
        <input type="email" name="email" id="email" placeholder="Entrez votre email." required/>
        <input type="email" name="email_confirm" id="email_confirm" placeholder="Confirmez votre email." required/>
    </div>
    <diV>
        <input type="checkbox" required/> J'accepte le bla bla.
    </div>
    <input type="submit"/>
</form>
<script src="/public/js/passwordVerify.js"></script>
<script src="/public/js/emailVerify.js"></script>
<?php
$content = ob_get_clean();
require('template.php');