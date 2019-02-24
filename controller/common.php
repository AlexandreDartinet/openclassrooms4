<?php
/**
 * Toutes les fonctions relatives à l'affichage et au traitement des données de tout le site
 */

/**
 * Retourne le numéro de page à partir du path
 * 
 * @param string $path : Chemin de la page actuelle
 * 
 * @return int : Numéro de la page (1 si aucune page dans le $path)
 */
function getPage(string $path) {
    if(preg_match('/page-\d+/', $path)) {
        return (int) preg_replace('/^.*page-(\d+).*$/', '$1', $path);
    }
    else {
        return 1;
    }
}