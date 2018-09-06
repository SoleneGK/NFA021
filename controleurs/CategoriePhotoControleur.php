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

		$pays_manager = new PaysManager($this->bdd);
		$pays = $pays_manager->obtenir_liste_pays();

		include 'vues/entete.php';
		include 'vues/menu.php';
		include 'vues/categorie_photo/liste_categories.php';
		include 'vues/pieddepage.php';
	}

	// Ajouter une catégorie de photos : affichage et traitement du formulaire
	function ajouter_categorie_photos() {
		$categorie_manager = new CategoriePhotoManager($this->bdd);
		$categories = $categorie_manager->obtenir_liste();

		$pays_manager = new PaysManager($this->bdd);
		$pays = $pays_manager->obtenir_liste_pays();

		if (isset($_POST['nom_categorie']) && isset($_POST['description_categorie'])) {
			$categorie_manager->ajouter_categorie($_POST['nom_categorie'], $_POST['description_categorie']);

			$message = '<p>Catégorie ajoutée</p>';
		}

		include 'vues/entete.php';
		include 'vues/menu_admin.php';
		include 'vues/categorie_photo/ajouter_categorie.php';
		include 'vues/pieddepage.php';
	}

	/* Afficher les informations d'une catégorie, et ses photos
	 * Afficher et gère la modification d'une catégorie et l'ajout d'une photo si les droits de l'utilisateur le permettent
	 */
	function afficher_categorie($id) {
		$id = (int)$id;

		$categorie_manager = new CategoriePhotoManager($this->bdd);
		
		// Modification demandée
		if (isset($_POST['nom_categorie']) && isset($_POST['description_categorie']) && $_SESSION['utilisateur']->droits[Section::TOUT] == Utilisateur::ADMIN) {
			$categorie_manager->modifier_categorie($id, $_POST['nom_categorie'], $_POST['description_categorie']);
		}

		$categories = $categorie_manager->obtenir_liste();

		$pays_manager = new PaysManager($this->bdd);
		$pays = $pays_manager->obtenir_liste_pays();

		include 'vues/entete.php';
		include 'vues/menu_admin.php';

		$categorie = $categorie_manager->obtenir_categorie($id);

		if (!$categorie)
			include 'vues/categorie_photo/aucune_categorie.php';
		else {
			$photos_manager = new PhotoManager($this->bdd);

			// Suppression d'une photo
			if (isset($_POST['supprimer_photo']) && isset($_POST['nom_fichier'])&& isset($_POST['nom_fichier']) && $_SESSION['utilisateur']->droits[Section::PHOTOS] <= Utilisateur::MODERATEUR) {
				unlink('public/images/photos/'.$_POST['nom_fichier']);
				$photos_manager->supprimer_photo((int)$_POST['id_photo']);
			}

			// Ajout d'une photo
			if (isset($_POST['ajouter_photo']) && isset($_FILES['image']) && isset($_POST['titre_photo']) && isset($_POST['description_photo']) && ($_SESSION['utilisateur']->droits[Section::TOUT] == Utilisateur::ADMIN || $_SESSION['utilisateur']->droits[Section::PHOTOS] <= Utilisateur::CONTRIBUTEUR))
				$message = PhotoControleur::ajouter_photo($this->bdd, $_POST['titre_photo'], $_SESSION['utilisateur']->id, 'image', $categorie->id, $_POST['description_photo']);

			// Obtenir les photos de la catégorie
			$photos = $photos_manager->obtenir_photos_categorie($categorie);

			// Affichage de la page
			include 'vues/categorie_photo/afficher_categorie_titre.php';

			if ($_SESSION['utilisateur']->droits[Section::TOUT] == Utilisateur::ADMIN) {
				include 'vues/categorie_photo/modifier_categorie_formulaire.php';
				include 'vues/photo/afficher_tableau_photos_admin.php';
				include 'vues/photo/ajouter_photo_formulaire.php';
			}
			elseif ($_SESSION['utilisateur']->droits[Section::PHOTOS] <= Utilisateur::MODERATEUR) {
				include 'vues/photo/afficher_tableau_photos_admin.php';
				include 'vues/photo/ajouter_photo_formulaire.php';
			}
			elseif ($_SESSION['utilisateur']->droits[Section::PHOTOS] <= Utilisateur::CONTRIBUTEUR) {
				include 'vues/photo/afficher_tableau_photos.php';
				include 'vues/photo/ajouter_photo_formulaire.php';
			}
			else {
				include 'vues/photo/afficher_tableau_photos.php';
			}
		}

		include 'vues/pieddepage.php';
	}

	/* Affiche la liste des catégories
	 * Affiche et gère la suppression d'une catégorie si les droits le permettent
	 */
	function afficher_liste_categories() {
		$categorie_manager = new CategoriePhotoManager($this->bdd);

		// Adapter les liens selon la section demandée
		if ($_GET['section'] == 'photos')
			$lien = 'admin.php?section=photos&categorie=';
		else
			$lien = 'admin.php?section=categories&id=';

		// Suppression d'une catégorie et des photos associées
		if (isset($_POST['supprimer_categorie']) && $_POST['id_categorie'] && $_SESSION['utilisateur']->droits[Section::TOUT] == Utilisateur::ADMIN) {

			$id_categorie = (int)$_POST['id_categorie'];
			// Supprimer les photos de cette catégorie, fichiers et entrées en bdd
			$photos_manager = new PhotoManager($this->bdd);
			$liste_photos = $photos_manager->obtenir_fichiers_photos_categorie($id_categorie);
			$photos_manager->supprimer_photos_categorie($id_categorie);

			foreach ($liste_photos as $photo) {
				unlink('public/images/photos/'.$photo['nom_fichier']);
			}

			$categorie_manager->supprimer_categorie($id_categorie);

			$message = '<p>Catégorie supprimée</p>';
		}

		// Suppression d'une photo
		if (isset($_POST['supprimer_photo']) && isset($_POST['nom_fichier'])&& isset($_POST['nom_fichier']) && ($_SESSION['utilisateur']->droits[Section::TOUT] == Utilisateur::ADMIN || $_SESSION['utilisateur']->droits[Section::PHOTOS] <= Utilisateur::MODERATEUR)) {
			unlink('public/images/photos/'.$_POST['nom_fichier']);
			$photos_manager = new PhotoManager($this->bdd);
			$photos_manager->supprimer_photo((int)$_POST['id_photo']);
		}


		$categories = $categorie_manager->obtenir_liste();

		$pays_manager = new PaysManager($this->bdd);
		$pays = $pays_manager->obtenir_liste_pays();

		include 'vues/entete.php';
		include 'vues/menu_admin.php';
		include 'vues/categorie_photo/liste_categories_admin.php';
		include 'vues/pieddepage.php';
	}
}