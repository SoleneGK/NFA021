<?php

class PhotoControleur {
	private $bdd;

	function __construct(PDO $bdd) {
		$this->bdd = $bdd;
	}

	// Afficher les informations d'une photo
	function afficher_photo($id) {
		$photo_manager = new PhotoManager($this->bdd);
		$photo = $photo_manager->obtenir_photo((int)$id);

		include 'vues/entete.php';

		if (!$photo)
			include 'vues/photo/aucune_photo.php';
		else
			include 'vues/photo/afficher_photo.php';

		include 'vues/pieddepage.php';
	}

	// Afficher les informations des photos d'une catégorie
	function afficher_categorie($id) {
		// Vérifier que la catégorie existe
		$categorie_manager = new CategoriePhotoManager($this->bdd);
		$categorie = $categorie_manager->obtenir_categorie((int)$id);

		include 'vues/entete.php';

		if (!$categorie)
			include 'vues/categorie_photo/aucune_categorie.php';
		else {
			$photo_manager = new PhotoManager($this->bdd);
			$photos = $photo_manager->obtenir_photos_categorie($categorie);
			include 'vues/photo/afficher_photos_categorie.php';
		}
		
		include 'vues/pieddepage.php';
	}


}