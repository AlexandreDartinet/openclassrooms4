<?php
ob_start();
?>
<header>
<div id="nav">
        <nav>
            <a href="/">Accueil</a>
            <a href="/archive/">Archives</a>
            <a href="/directory/">Annuaire</a>
            <a href="/contact/">Contactez-nous</a>
<?php
if($_SESSION['user']->level >= User::LEVEL_MODERATOR) {
?>
            <a href="/admin/">Administration</a>
<?php
}
?>
        </nav>
<?php
if($_SESSION['user']->id != 0) {
?>
        <a href="/profile/edit/"><?= $_SESSION['user']->name ?></a>
        <a href="/logout/">Se déconnecter</a>
<?php
}
else {
?>
        <form method="post" action="<?= $_GET['path'] ?>">
            <input type="hidden" name="action" value="login" required/>
            <input type="text" name="name" placeholder="Pseudonyme" required/>
            <input type="password" name="password" placeholder="Mot de passe" required/>
            <input type="submit"/>
        </form>
        <a href="/register/">S'inscrire</a>
<?php
}
?>
</div>
</header>

<?php


$header = ob_get_clean();