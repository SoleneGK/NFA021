<?php

class CategoriePhotoControleur {
	private $bdd;

	function __construct(PDO $bdd) {
		$this->bdd = $bdd;
	}

	// Afficher la liste des catégories
	function afficher_liste() {
		$categorie_manager = new CategoriePhotoManager($this->bdd);
		$categories = $categorie_manager->obtenir_liste();

		include 'vues/entete.php';
		include 'vues/categorie_photo/liste_categories.php';
		include 'vues/pieddepage.php';
	}

	function ajouter_categorie_photos() {
		if (isset($_POST['nom']) && isset($_POST['description'])) {
			$categorie_manager = new CategoriePhotoManager($this->bdd);
			$categorie_manager->ajouter_categorie($_POST['nom'], $_POST['description']);

			$message = '<p>Catégorie ajoutée</p>';
		}

		include 'vues/entete.php';
		include 'vues/categorie_photo/ajouter_categorie.php';
		include 'vues/pieddepage.php';
	}

	function afficher_categorie_photos($id) {
		$categorie_manager = new CategoriePhotoManager($this->bdd);

		// Modification demandée
		if (isset($_POST['nom_categorie']) && isset($_POST['description_categorie'])) {
			$categorie_manager->modifier_categorie($_GET['id'], $_POST['nom_categorie'], $_POST['description_categorie']);
		}

		$categorie = $categorie_manager->obtenir_categorie($_GET['id']);

		include 'vues/entete.php';

		if (!$categorie)
			include 'vues/categorie_photo/aucune_categorie.php';
		else {
			$photos_manager = new PhotoManager($this->bdd);

			if (isset($_POST['ajouter_photo']) && isset($_FILES['image']) && isset($_POST['titre_photo']) && isset($_POST['description_photo']))
				$message = PhotoControleur::ajouter_photo($this->bdd, $_POST['titre_photo'], $_SESSION['utilisateur']->id, 'image', $categorie->id, $_POST['description_photo']);

			// Obtenir les photos de la catégorie
			$photos = $photos_manager->obtenir_photos_categorie($categorie);

			include 'vues/categorie_photo/afficher_categorie.php';
		}

		include 'vues/pieddepage.php';
	}

	function afficher_liste_categories_photos() {
		$categorie_manager = new CategoriePhotoManager($this->bdd);

		if (isset($_POST['supprimer']) && $_POST['id']) {
			$id_categorie = (int)$_POST['id'];
			// Supprimer les photos de cette catégorie, fichiers et entrées en bdd
			$photos_manager = new PhotoManager($this->bdd);
			$liste_photos = $photos_manager->obtenir_fichiers_photos_categorie($id_categorie);
			$photos_manager->supprimer_photos_categorie($id_categorie);

			foreach ($liste_photos as $photo) {
				unlink('public/images/photos/'.$photo['nom_fichier']);
			}

			// Supprimer la catégorie
			$categorie_manager->supprimer_categorie($id_categorie);

			$message = '<p>Catégorie supprimée</p>';
		}

		$categories = $categorie_manager->obtenir_liste();

		include 'vues/entete.php';
		include 'vues/categorie_photo/liste_categories_admin.php';
		include 'vues/pieddepage.php';
	}
}