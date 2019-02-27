<?php
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
            throw new Exception("ImageManager: Erreur de requête getImageById($id).");
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
}