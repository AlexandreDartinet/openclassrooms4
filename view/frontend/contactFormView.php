<?php
/**
 * Gère l'affichage du formulaire de contact du site.
 * 
 * Le formulaire envoie à / en post les valeurs suivantes :
 * @var string action : sendContactForm
 * @var string name : Nom de la personne qui envoie le formulaire (required)
 * @var string email : Email de la personne qui envoie le formulaire (required)
 * @var string email_confirm : Confirmation de l'email (doit être identique à email) (required)
 * @var string message : Message de l'utilisateur (required)
 */
ob_start();
?>
<h2>Formulaire de contact</h2>

<form method="post" action="/">
    <input type="hidden" name="action" value="sendContactForm" required/>
    <div>
        <label for="name">Votre nom :</label><br/>
        <input type="text" name="name" id="name" value="<?= $name ?>" placeholder="Entrez votre nom." required/>
    </div>
    <div>
        <label for="email">Votre email :</label><br/>
        <input type="email" name="email" id="email" value="<?= $email ?>" placeholder="Entrez votre email." required/>
        <input type="email" name="email_confirm" id="email_confirm" value="<?= $email ?>" placeholder="Confirmez votre email" required/>
    </div>
    <div>
        <label for="message">Votre message :</label><br/>
        <textarea name="message" id="message" required></textarea>
    </div>
    <div>
    <input type="checkbox" required/> J'accepte bla bla
    </div>
    <input type="submit"/>

</form>


<?php
$scripts = ['<script src="/public/js/emailVerify.js"></script>'];
$content = ob_get_clean();
require('template.php');