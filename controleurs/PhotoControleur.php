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

	static function ajouter_photo($bdd, $titre, $id_utilisateur, $image, $id_categorie, $description, $verifier_categorie = false) {
		// Vérifier que la catégorie existe
		if ($verifier_categorie) {
			$categorie_manager = new CategoriePhotoManager($bdd);
			$categorie = $categorie_manager->obtenir_categorie($id_categorie);

			if (!$categorie)
				$message = 'Cette catégorie n\'existe pas.';
		}

		if (!isset($message)) {
			// Vérifier que l'image est une photo
			$extension = strtolower(pathinfo($_FILES[$image]['name'], PATHINFO_EXTENSION));
			$extensions_autorisees = ['jpg', 'jpeg', 'gif', 'png'];

			if (!in_array($extension, $extensions_autorisees))
				$message = 'Le fichier n\'est pas une photo';
			else {
				// Création d'un nom de fichier unique pour le fichier et déplacemant dans le dossier définitif
				$nom_image = md5(uniqid()).'.'.$extension;
				
				if (!move_uploaded_file($_FILES[$image]['tmp_name'], 'public/images/photos/'.$nom_image))
					$reponse = 'Erreur lors de la copie du fichier';
				else {
					$photo_manager = new PhotoManager($bdd);
					$photo_manager->ajouter_photo($titre, $id_utilisateur, $nom_image, $id_categorie, $description);
					$message = 'Photo ajoutée';
				}
			}

		}

		return '<p>'.$message.'</p>';
	}
}