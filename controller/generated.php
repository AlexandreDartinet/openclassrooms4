<?php
namespace DartAlex;
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
    $imageManager = new ImageManager();
    $images = $imageManager->getImages("all", Image::TYPE_POST);
    $json = [];
    foreach($images as $image) {
        $image_json = new \stdClass();
        $image_json->title = $image->title;
        $image_json->value = $image->url;
        $json[] = $image_json;
    }
    displayJson($json);
}

/**
 * Affiche le fichier js init.js
 * 
 * @return void
 */
function displayInitJs($path) {
    header("Content-Type: application/javascript; charset=UTF-8");
    $page = getPage($path);
    $path = preg_replace('/page-\d+\//', '', $path);
    $site_url = 'https://'.SITE_URL.'/';
    $init = 
"const siteUrl = '$site_url';
const path = '$path';
let curPage = $page;";
    echo $init;
}