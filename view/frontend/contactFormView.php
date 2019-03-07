<?php
namespace DartAlex;
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
<h2 class="title is-3">Formulaire de contact</h2>

<div id="contact-form" class="box"><form method="post" action="/">
    <input type="hidden" name="action" value="sendContactForm"/>
    <div class="field">
        <label for="name" class="label">Votre nom</label>
        <div class="control">
            <input class="input" type="text" name="name" id="name" value="<?= $name ?>" placeholder="Entrez votre nom." required/>
        </div>
    </div>
    <div class="field">
        <label for="email" class="label">Votre email</label>
        <div class="field-body">
            <div class="field">
                <div class="control is-expanded">
                    <input class="input" type="email" name="email" id="email" value="<?= $email ?>" placeholder="Entrez votre email." required/>
                </div>
            </div>
            <div class="field">
                <div class="control is-expanded">
                    <input class="input" type="email" name="email_confirm" id="email_confirm" value="<?= $email ?>" placeholder="Confirmez votre email" required/>
                </div>
            </div>
        </div>
    </div>
    <div class="field">
        <label for="message" class="label">Votre message</label>
        <div class="control">
            <textarea class="textarea" name="message" id="message" required></textarea>
        </div>
    </div>
    <div class="field">
        <div class="control">
            <label class="checkbox">
                <input type="checkbox" required/>
                J'accepte la <a href="/polconf/" target="_blank">politique de confidentialité</a>.
            </label>
        </div>
    </div>
    <div class="field is-grouped is-grouped-centered">
        <div class="control">
            <input class="button is-primary" type="submit" name="Envoyer"/>
        </div>
    </div>

</form></div>


<?php
$scripts = ['<script src="/public/js/emailVerify.js"></script>'];
$content = ob_get_clean();
require('template.php');