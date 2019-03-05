<?php
namespace DartAlex;
/**
 * Classe gérant les interactions avec la bdd en rapport avec la table images
 * 
 * @var string TABLE_NAME : Nom de la table
 * 
 * @see Manager : classe parente
 */
class ImageManager extends Manager {

    const IMAGE_PAGE = 20;
    const TABLE_NAME = 'images';
    
    /**
     * Retourne la liste de toutes les images
     * 
     * @param mixed $page : page à retourner (défaut 1) "all" si on veut récupérer toutes les images
     * @param mixed $type : Type d'image à rechercher (par défaut POST) "all" si on veut récupérer tous les types
     * 
     * @return array : Tableau d'objets Image représentant les images
     */
    public function getImages($page = 1, $type = Image::TYPE_POST) {
        if(is_int($type)) {
            $queryStart = 'SELECT * FROM images WHERE `type`='.$type;
        }
        elseif($type = "all") {
            $queryStart = 'SELECT * FROM images';
        }
        else {
            throw new \Exception("ImageManager: getImages($page, $type): Paramètre \$type($type) invalide.");
        }
        if(is_int($page)) {
            $req = $this->_db->prepare($queryStart.' LIMIT '.(($page-1)*self::IMAGE_PAGE).','.$page*self::IMAGE_PAGE);
        }
        elseif($page = "all") {
            $req = $this->_db->prepare($queryStart);
        }
        else {
            throw new \Exception("ImageManager: getImages($page, $type): Paramètre \$page($page) invalide.");
        }
        if($req->execute()) {
            $images = [];
            while($line = $req->fetch()) {
                $image = new Image($line);
                $images[] = $image;
            }
            $req->closeCursor();
            return $images;
        }
        else {
            throw new \Exception("ImageManager: getImages($page, $type): Erreur de requête.");
        }
    }

    /**
     * Retourne le image associé à un identifiant
     * 
     * @param int $id : Identifiant du image à retourner
     * 
     * @return Image : Image demandé false si aucun
     */
    public function getImageById(int $id) {
        if($req = $this->getBy('id', $id)) {
            if($res = $req->fetch()) {
                $image = new Image($res);
            }
            else {
                $image = false;
            }
            $req->closeCursor();
            return $image;
        }
        else {
            throw new \Exception("ImageManager: Erreur de requête getImageById($id).");
        }
    }

    /**
     * Enregistre un nouveau image, ou en modifie un existant dans la bdd
     * Enregistre si l'id est à 0, sinon modifie.
     * 
     * @param Image $image : Le image à mettre à jour, ou enregistrer
     * 
     * @return boolean : true si la requête a été executée avec succès, false sinon.
     */
    public function setImage(Image $image) {
        if ($image->id == 0) {
            $req = $this->_db->prepare('INSERT INTO images(id_user, `file_name`, `type`, title ) VALUES (?, ?, ?, ?)');
            $exec = $req->execute([
                $image->id_user,
                $image->file_name,
                $image->type,
                $image->title
            ]);
            
        }
        else {
            $req = $this->_db->prepare('UPDATE images SET id_user=?, `file_name`=?, `type`=?, title=? WHERE id=?');
            $exec = $req->execute([
                $image->id_user,
                $image->file_name,
                $image->type,
                $image->title,
                $image->id
            ]);
        }
        $req->closeCursor();
        return $exec;
    }

    /**
     * Supprime un image de la bdd
     * 
     * @param Image $image : Image à supprimer
     * 
     * @return boolean : true si succès
     */
    public function removeImage(Image $image) {
        return $this->removeBy('id', $image->id);
    }

    /**
     * Retire un utilisateur des images et le change en anonyme.
     * 
     * @param User $user : Utilisateur qu'on souhaite retirer
     * 
     * @return boolean : true si succès
     */
    public function removeUser(User $user) {
        $req = $this->_db->prepare('UPDATE images SET id_user=0 WHERE id_user=?');
        return $req->execute([$user->id]);
    }
}