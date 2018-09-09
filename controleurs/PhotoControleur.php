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
	function afficher_photo($id, $admin = false) {
		$photo_manager = new PhotoManager($this->bdd);
		$photo = $photo_manager->obtenir_photo((int)$id);

		// Modification d'une photo
		if ($admin && ($_SESSION['utilisateur']->droits[Section::TOUT] == Utilisateur::ADMIN || $_SESSION['utilisateur']->droits[Section::PHOTOS] == Utilisateur::MODERATEUR || ($_SESSION['utilisateur']->droits[Section::PHOTOS] == Utilisateur::CONTRIBUTEUR && $photo->utilisateur->id == $_SESSION['utilisateur']->id))) {
			$utilisateur_manager = new UtilisateurManager($this->bdd);
			$utilisateurs = $utilisateur_manager->obtenir_liste_tous_utilisateurs();

			if (isset($_POST['titre_photo']) && isset($_POST['id_categorie']) && isset($_POST['id_utilisateur']) && isset($_POST['description_photo'])) {
				// Vérifier que la catégorie et l'utilisateur existent
				if (!$this->existe_objet_id($_POST['id_categorie'], $categories))
					$message_erreur = 'Cette catégorie n\'existe pas.';
				elseif (!$this->existe_objet_id($_POST['id_utilisateur'], $utilisateurs))
					$message_erreur = 'Cet utilisateur n\'existe pas.';
				else {
					$photo_manager->modifier_photo($photo->id, trim($_POST['titre_photo']), $_POST['id_categorie'], $_POST['id_utilisateur'], trim($_POST['description_photo']));
					$photo = $photo_manager->obtenir_photo((int)$id);
				}
			}		
		}

		// Afichage de la page
		$categorie_manager = new CategoriePhotoManager($this->bdd);
		$categories = $categorie_manager->obtenir_liste();

		$pays_manager = new PaysManager($this->bdd);
		$pays = $pays_manager->obtenir_liste_pays();

		include 'vues/entete.php';

		if ($admin)
			include 'vues/menu_admin.php';
		else
			include 'vues/menu.php';

		include 'vues/photo/afficher_photo.php';
		include 'vues/pieddepage.php';
	}

	/* Ajouter une photo
	 * Vérifier que les informations fournies sont correctes et faire l'ajout en bdd
	 * Renvoie un message informant du status de l'opération
	 */
	static function ajouter_photo($bdd, $titre, $id_utilisateur, $image, $id_categorie, $description, $verifier_categorie = false) {
		// Vérifier que la catégorie existe
		if ($verifier_categorie) {
			$categorie_manager = new CategoriePhotoManager($bdd);
			$categorie = $categorie_manager->obtenir_categorie($id_categorie);

			if (!$categorie) {
				$reponse['status'] = false;
				$status = 'CATEGORIE_INEXISTANTE';
			}
		}

		if (!isset($message)) {
			// Vérifier que l'image est une photo
			$extension = strtolower(pathinfo($_FILES[$image]['name'], PATHINFO_EXTENSION));
			$extensions_autorisees = ['jpg', 'jpeg', 'gif', 'png'];

			if (!in_array($extension, $extensions_autorisees)) {
				$status = 'PAS_UNE_PHOTO';
			}
			else {
				// Création d'un nom de fichier unique pour le fichier et déplacemant dans le dossier définitif
				$nom_image = md5(uniqid()).'.'.$extension;
				
				if (!move_uploaded_file($_FILES[$image]['tmp_name'], 'public/images/photos/'.$nom_image)) {
					$status = 'ERREUR_COPIE';
				}
				else {
					$photo_manager = new PhotoManager($bdd);
					$resultat = $photo_manager->ajouter_photo(trim($titre), $id_utilisateur, $nom_image, $id_categorie, trim($description));

					if ($resultat)
						$status = 'OK';
					else
						$status = 'ERREUR_AJOUT_BDD';
				}
			}

		}

		return $status;
	}

	function ajouter_photo_page() {
		// Ajouter la photo
		if (isset($_POST['titre_photo']) && isset($_FILES['image']) && isset($_POST['id_categorie']) && isset($_POST['description_photo'])) {
			//$status = self::ajouter_photo($this->bdd, trim($_POST['titre_photo']), $_SESSION['utilisateur']->id, 'image', (int)$_POST['id_categorie'], trim($_POST['description_photo']), true);

			switch ($status) {
				case 'OK':
					$message_succes = 'Photo ajoutée';
					unset($_POST['titre_photo']);
					unset($_POST['description_photo']);
					// La catégorie est conservée pour faciliter l'ajout de plusieurs photos de la même catégorie
					break;
				
				case 'CATEGORIE_INEXISTANTE':
					$message_erreur = 'Cette catégorie n\'existe pas.';
					unset($_POST['id_categorie']);
					break;

				case 'PAS_UNE_PHOTO':
					$message_erreur = 'Le fichier n\'est pas une photo.';
					break;

				case 'ERREUR_COPIE':
					$message_erreur = 'Une erreur est survenue lors du traitement du fichier.';
					break;

				case 'ERREUR_AJOUT_BDD':
					$message_erreur = 'Une erreur est survenue lors de l\'enregistrement des informations.';
					break;
			}
		}

		$categorie_manager = new CategoriePhotoManager($this->bdd);
		$categories = $categorie_manager->obtenir_liste();

		$pays_manager = new PaysManager($this->bdd);
		$pays = $pays_manager->obtenir_liste_pays();

		include 'vues/entete.php';
		include 'vues/menu_admin.php';
		include 'vues/photo/ajouter_photo.php';
		include 'vues/pieddepage.php';
	}
}