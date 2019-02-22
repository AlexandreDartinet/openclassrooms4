<?php
$title = "Formulaire de contact";
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
<script src="/public/js/emailVerify.js"></script>

<?php
$content = ob_get_clean();
require('template.php');