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

		$categorie_manager = new CategoriePhotoManager($this->bdd);
		$categories = $categorie_manager->obtenir_liste();

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

	// Redimensionner une image
	static function redimensionner_image($bdd, $nom_fichier, $type_fichier, $largeur_source, $hauteur_source, $taille_max, $dossier_destination) {
		if ($type_fichier == 'jpg')
			$type_fichier = 'jpeg';

		// Création de l'image source
		switch ($type_fichier) {
			case 'jpeg':
				$source = imagecreatefromjpeg('public/images/photos/original/'.$nom_fichier);
				break;
			case 'gif':
				$source = imagecreatefromgif('public/images/photos/original/'.$nom_fichier);
				break;
			case 'png':
				$source = imagecreatefrompng('public/images/photos/original/'.$nom_fichier);
				break;
		}

		// Calcul des dimensions puis création de l'image redimensionnée
		if ($largeur_source == $hauteur_source) {
			$largeur_destination = $taille_max;
			$hauteur_destination = $taille_max;
		}
		else if ($largeur_source > $hauteur_source) {
			$largeur_destination = $taille_max;
			$hauteur_destination = floor($taille_max * $hauteur_source / $largeur_source);
		}
		else {
			$hauteur_destination = $taille_max;
			$largeur_destination = floor($taille_max * $largeur_source / $hauteur_source);
		}

		$destination = imagecreatetruecolor($largeur_destination, $hauteur_destination);

		// Redimensionnement de l'image
		imagecopyresampled($destination, $source, 0, 0, 0, 0, $largeur_destination, $hauteur_destination, $largeur_source, $hauteur_source);

		// Enregistrement du fichier
		switch ($type_fichier) {
			case 'jpeg':
				imagejpeg($destination, 'public/images/photos/'.$dossier_destination.'/'.$nom_fichier);
				break;
			case 'gif':
				imagegif($destination, 'public/images/photos/'.$dossier_destination.'/'.$nom_fichier);
				break;
			case 'png':
				imagepng($destination, 'public/images/photos/'.$dossier_destination.'/'.$nom_fichier);
				break;
		}
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

			$max_image_size = 1024 * 1024; // Max 1Mo

			if (!in_array($extension, $extensions_autorisees))
				$status = 'PAS_UNE_PHOTO';
			elseif($_FILES[$image]['error'] == 2 || filesize($_FILES[$image]['tmp_name']) > $max_image_size)
				$status = 'FICHIER_TROP_GRAND';
			else {
				// Création d'un nom de fichier unique pour le fichier et déplacemant dans le dossier définitif
				$nom_image = md5(uniqid()).'.'.$extension;
				
				if (!move_uploaded_file($_FILES[$image]['tmp_name'], 'public/images/photos/original/'.$nom_image)) {
					$status = 'ERREUR_COPIE';
				}
				else {
					// Création des images de tailles réduites
					list($largeur_photo, $hauteur_photo) = getimagesize('public/images/photos/original/'.$nom_image);

					if ($largeur_photo <= 526 && $hauteur_photo <= 526) {
						copy('public/images/photos/original/'.$nom_image, 'public/images/photos/526/'.$nom_image);
						copy('public/images/photos/original/'.$nom_image, 'public/images/photos/968/'.$nom_image);
					}
					elseif ($largeur_photo <= 968 && $hauteur_photo <= 968) {
						self::redimensionner_image($bdd, $nom_image, $extension, $largeur_photo, $hauteur_photo, 526, '526');
						copy('public/images/photos/original/'.$nom_image, 'public/images/photos/968/'.$nom_image);
					}
					else {
						self::redimensionner_image($bdd, $nom_image, $extension, $largeur_photo, $hauteur_photo, 526, '526');
						self::redimensionner_image($bdd, $nom_image, $extension, $largeur_photo, $hauteur_photo, 968, '968');
					}

					// Enregistrement en base de données
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
			$status = self::ajouter_photo($this->bdd, trim($_POST['titre_photo']), $_SESSION['utilisateur']->id, 'image', (int)$_POST['id_categorie'], trim($_POST['description_photo']), true);

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

				case 'FICHIER_TROP_GRAND':
					$message_erreur = 'Le fichier envoyé est trop grand';
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