<?php

class PhotoControleur {
	private $bdd;

	function __construct(PDO $bdd) {
		$this->bdd = $bdd;
	}

	/* Vérifie s'il existe un objet avec l'id $id dans un array d'objets
	 * Renvoie un booleen
	 */

	function existe_objet_id($id, $array) {
		$existe = false;

		foreach($array as $objet) {
			if ($objet->id == $id) {
				$existe = true;
				break;
			}
		}

		return $existe;
	}

	// Afficher les informations d'une photo
	function afficher_photo($id, $admin = false, $droits_utilisateur = null) {
		$photo_manager = new PhotoManager($this->bdd);
		$photo = $photo_manager->obtenir_photo((int)$id);

		include 'vues/entete.php';

		if (!$photo)
			include 'vues/photo/aucune_photo.php';
		else {
			if ($admin) {
				if ($droits_utilisateur[Section::TOUT] == Utilisateur::ADMIN || $droits_utilisateur[Section::PHOTOS] == Utilisateur::MODERATEUR || ($droits_utilisateur[Section::PHOTOS] == Utilisateur::CONTRIBUTEUR && $photo->utilisateur->id == $_SESSION['utilisateur']->id)) {
					$categorie_manager = new CategoriePhotoManager($this->bdd);
					$categories = $categorie_manager->obtenir_liste();

					$utilisateur_manager = new UtilisateurManager($this->bdd);
					$utilisateurs = $utilisateur_manager->obtenir_liste_tous_utilisateurs();

					if (isset($_POST['titre_photo']) && isset($_POST['id_categorie']) && isset($_POST['id_utilisateur']) && isset($_POST['description_photo'])) {
						// Vérifier que la catégorie et l'utilisateur existent
						if (!$this->existe_objet_id($_POST['id_categorie'], $categories))
							$message = '<p>Cette catégorie n\'existe pas.</p>';
						elseif (!$this->existe_objet_id($_POST['id_utilisateur'], $utilisateurs))
							$message = '<p>Cet utilisateur n\'existe pas.</p>';
						else {
							$photo_manager->modifier_photo($photo->id, $_POST['titre_photo'],$_POST['id_categorie'], $_POST['id_utilisateur'], $_POST['description_photo']);
							$message = '<p>Photo modifiée</p>';
							$photo = $photo_manager->obtenir_photo((int)$id);
						}
					}

					include 'vues/photo/afficher_photo_admin.php';
					include 'vues/photo/modifier_photo.php';
				}
				else
					include 'vues/photo/afficher_photo_admin.php';
			}
			else
				include 'vues/photo/afficher_photo.php';
		}

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

	function ajouter_photo_page() {
		// Ajouter la photo
		if (isset($_POST['titre_photo']) && isset($_FILES['image']) && isset($_POST['id_categorie']) && isset($_POST['description_photo']))
			$message = self::ajouter_photo($this->bdd, $_POST['titre_photo'], $_SESSION['utilisateur']->id, 'image', (int)$_POST['id_categorie'], $_POST['description_photo'], true);

		// Obtenir les catégories
		$categorie_manager = new CategoriePhotoManager($this->bdd);
		$categories = $categorie_manager->obtenir_liste();

		include 'vues/entete.php';
		include 'vues/photo/ajouter_photo.php';
		include 'vues/pieddepage.php';
	}

	function afficher_photos_categorie($id_categorie) {

	}

	function afficher_liste_categories_photos() {

	}
}