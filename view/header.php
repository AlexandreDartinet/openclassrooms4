<?php
namespace DartAlex;
/**
 * Gère l'affichage du header de la partie frontend
 */
ob_start();
?>
        
<div id="nav" class="navbar is-fixed-top is-dark" role="navigation" aria-label="main navigation">
        <div class="navbar-brand">
                <a class="navbar-item" href="/"><span class="icon"><i class="fas fa-home"></i></span>&nbsp;Accueil</a>
                <a role="button" aria-label="menu" aria-expanded="false" data-target="navbarMenu" class="navbar-burger burger">
                        <span aria-hidden="true"></span>
                        <span aria-hidden="true"></span>
                        <span aria-hidden="true"></span>
                </a>
        </div>
        <div class="navbar-menu is-dark" id="navbarMenu">
                <div class="navbar-start is-dark">
                        <?= $menu ?>
                </div>
                <div class="navbar-end">
        
        
<?php
if($_SESSION['user']->id != 0) { // Si l'utilisateur est authentifié, on affiche un lien vers l'édition de son profil, et pour se déconnecter
?>
                        <a class="navbar-item" href="/profile/edit/"><span class="icon"><i class="fas fa-user"></i></span>&nbsp;<?= htmlspecialchars($_SESSION['user']->name) ?></a>
                        <a title="Déconnexion" class="navbar-item" href="/logout/"><span class="icon has-text-danger"><i class="fas fa-times"></span></i>&nbsp;Déconnexion</a>
<?php
}
else { // Si l'utilisateur est anonyme, on affiche le formulaire de connexion, un lien pour s'inscrire et un lien en cas d'oubli du mot de passe
/**
 * Le formulaire renvoie en post vers le chemin actuel les valeurs suivantes :
 * @var string action : login
 * @var string name : Le nom de l'utilisateur (required)
 * @var string password : Le mot de passe de l'utilisateur (required)
 */
?>
                        <div class="navbar-item has-dropdown">
                                <a class="navbar-link">Connexion</a>
                                <div class="navbar-dropdown is-boxed is-right">
                                        <div class="navbar-item">
                                                <form method="post" action="<?= PATH ?>">
                                                        <input type="hidden" name="action" value="login" required/>
                                                        <div class="field">
                                                                <div class="control">
                                                                        <input class="input" type="text" name="name" placeholder="Pseudonyme" required/>
                                                                </div>
                                                        </div>
                                                        <div class="field">
                                                                <div class="control">
                                                                        <input class="input" type="password" name="password" placeholder="Mot de passe" required/>
                                                                </div>
                                                        </div>
                                                        
                                                        <input class="button" type="submit" id="login-submit"/>
                                                </form>
                                        </div>
                                        <hr class="navbar-divider">
                                        <a class="navbar-item" href="/recover/">Mot de passe oublié</a>
                                </div>
                        </div>
                        
                        <a class="navbar-item" href="/register/">S'inscrire</a>
                        
<?php
}
?>
                </div>
        </div>
</div>
<!-- <header class="hero">
        <div class="hero-body">
                <div class="container">
                        <h1 class="title is-1"></h1>
                </div>
        </div>
</header> -->

<?php


$header = ob_get_clean();