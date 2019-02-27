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
    if(file_exists(Image::PATH.$filename)) {
        $image = imagecreatefrompng(Image::PATH.$filename);
    }
    else {
        $image = imagecreatefrompng(Image::PATH."unknown.png");
    }
    imagepng($image);
}