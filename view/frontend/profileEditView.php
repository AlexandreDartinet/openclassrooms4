<?php
$title = "Modification de votre profil";
ob_start();
?>
<h2>Modification de votre profil</h2>
<?php
if(preg_match('/\/retry\//', $_GET['path'])) {
    $field = preg_replace('/^.*\/retry\/(\w+)\/.*$/', '$1', $_GET['path']);
?>
<p>Le <?= $field ?> que vous avez choisi est déjà utilisé ou invalide.</p>
<?php
}
?>
<form method="post" action="/">
    <input type="hidden" name="action" value="modifyUser" required/>
    <input type="hidden" name="id" value="<?= $user->id ?>" required/>
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
        <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirmez le mot de passe"/>
    </div>
    <div>
        <label for="name_display">Nom affiché :</label>
        <input type="text" name="name_display" id="name_display" placeholder="Entrez le nom que vous voulez afficher." value="<?= $user->name_display ?>" required/>
    </div>
    <div>
        <label for="email">Email :</label>
        <input type="email" name="email" id="email" placeholder="Entrez votre email." value="<?= $user->email ?>" required/>
        <input type="email" name="confirm_email" id="confirm_email" placeholder="Confirmez votre email." value="<?= $user->email ?>" required/>
    </div>
    <input type="submit"/>
</form>
<script src="/public/js/passwordVerify.js"></script>
<script src="/public/js/emailVerify.js"></script>
<?php
$content = ob_get_clean();
require('template.php');
