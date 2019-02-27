<?php
/**
 * Gère l'affichage du contenu généré dynamiquement
 */

/**
 * Affiche une image dynamiquement
 * 
 * @param string $filename : Nom du fichier à ouvrir
 * 
 * @return void
 */
function displayImage(string $filename) {
    header("Content-type: image/png");
    if(file_exists("private/images/$filename")) {
        $image = imagecreatefrompng("private/images/$filename");
    }
    else {
        $image = imagecreatefrompng("private/images/unknown.png");
    }
    imagepng($image);
}