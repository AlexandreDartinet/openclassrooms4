<?php
/**
 * Toutes les fonctions relatives à l'affichage et au traitement des données de la partie backend du site
 */

/**
 * Fonctions relatives à l'affichage
 */

/**
 * Affiche la page d'accueil de l'interface d'administration
 * 
 * @return void
 */
function viewAdmin() {
    $title = "Accueil";

    require('view/backend/adminView.php');
}

/**
 * Fonctions relatives au traitement des données
 */