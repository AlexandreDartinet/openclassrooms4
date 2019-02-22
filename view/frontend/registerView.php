<?php
$title = "Inscription";
ob_start();
?>
<h2>Formulaire d'inscription :</h2>
<?php
if(preg_match('/\/retry\//', $_GET['path'])) {
    $field = preg_replace('/^.*\/retry\/(\w+)\/.*$/', '$1', $_GET['path']);
?>
<p>Le <?= $field ?> que vous avez choisi est déjà utilisé.</p>
<?php
}
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
        <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirmez le mot de passe" required/>
    </div>
    <div>
        <label for="name_display">Nom affiché :</label>
        <input type="text" name="name_display" id="name_display" placeholder="Entrez le nom que vous voulez afficher." required/>
    </div>
    <div>
        <label for="email">Email :</label>
        <input type="email" name="email" id="email" placeholder="Entrez votre email." required/>
        <input type="email" name="confirm_email" id="confirm_email" placeholder="Confirmez votre email." required/>
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