<?php
namespace DartAlex;
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

/**
 * Retourne le sélecteur de pages à afficher
 * 
 * @param int $pages_nbr : Nombre de pages total
 * @param int $page : Page actuelle
 * @param string $path : Chemin à ajouter avant la page
 * 
 * @return string : Retourne chaine vide s'il n'y a qu'une page, div page-selector remplie sinon
 */
function pageSelector(int $pages_nbr, int $page, string $path) {
    if($pages_nbr == 1) {
        return '';
    }
    $path = preg_replace('/page-\d+\//', '', $path);
    $selector = '<nav id="page-selector" class="pagination is-centered" role="navigation" aria-label="pagination">';
    if($page > 1) {
        $prevPage = $path."page-".($page-1)."/";
        $selector .= "<a class='page-selector-item pagination-previous' id='page-prev' href='$prevPage'>&lt;Précédente</a>";
    }
    if($page < $pages_nbr) {
        $nextPage = $path."page-".($page+1)."/";
        $selector .= "<a class='page-selector-item pagination-next' id='page-next' href='$nextPage'>Suivante&gt;</a>";
    }
    $selector .= "<ul class='pagination-list'>";
    for($i = 1; $i <= $pages_nbr; $i++) {
        $pageLink = $path."page-".$i."/";
        $selector .= "<li><a class='page-selector-item pagination-link".(($i == $page)?' page-current is-current':'')."' id='page-$i' href='$pageLink'>$i</a></li>";
    }
    $selector .= "</ul></nav>";
    return $selector;
}

/**
 * Affiche en json l'objet donné en argument
 * 
 * @param ? $object : Objet à afficher
 * 
 * @return void
 */
function displayJson($object) {
    header("Content-Type: application/json; charset=UTF-8");
    echo json_encode($object);
}

/**
 * Affiche une erreur sous forme de json
 * 
 * @param string $error : Erreur à afficher
 * 
 * @return void
 */
function displayErrorJson(string $error) {
    $json = new \stdClass();
    $json->error = $error;
    displayJson($json);
}

/**
 * Affiche un succès sous forme de json
 * 
 * @param string $success : Succès à afficher
 * 
 * @return void
 */
function displaySuccessJson(string $success) {
    $json = new \stdClass();
    $json->success = $success;
    displayJson($json);
}