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

/**
 * Affiche le json images.json
 * 
 * @return void
 */
function displayImagesJson() {
    header("Content-Type: application/json; charset=UTF-8");
    $imageManager = new ImageManager();
    $images = $imageManager->getImages("all", Image::TYPE_POST);
    $json = [];
    foreach($images as $image) {
        $image_json = new stdClass();
        $image_json->title = $image->title;
        $image_json->value = $image->url;
        $json[] = $image_json;
    }
    echo json_encode($json);
}