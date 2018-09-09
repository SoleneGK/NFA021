<?php
require_once 'outils/afficher.php';
require_once 'outils/Autoloader.php';
Autoloader::enregistrer();

session_start();

$bdd = new Bdd();

define('NOMBRE_ARTICLES_PAR_PAGE', 5);

// Traitement des demandes de connexion
if (!isset($_SESSION['utilisateur']) && isset($_POST['mail_connexion']) && isset($_POST['mot_de_passe_connexion'])) {
	$connexion = new UtilisateurControleur($bdd->bdd);
	$connexion->connecter($_POST['mail_connexion'], $_POST['mot_de_passe_connexion']);
}

// Traitement des demandes de déconnexion
if (isset($_GET['deco'])) {
	$connexion = new UtilisateurControleur($bdd->bdd);
	$connexion->deconnecter();
}

// S'il n'y a pas d'utilisateur connecté, l'accès est autorisé uniquement à la page de connexion et à celle de récupération du mot de passe
if (!isset($_SESSION['utilisateur'])) {
	$controleur = new AccueilControleur($bdd->bdd);

	// Forme de l'URL : admin.php?mot_de_passe_perdu
	if(isset($_GET['mot_de_passe_perdu'])) {
		//Forme de l'URL : admin.php?mot_de_passe_perdu&mail=[mail]&code=[code]
		if (isset($_GET['mail']) && isset($_GET['code']))
			$controleur->afficher_modifier_mot_de_passe_perdu();
		else
			$controleur->afficher_demander_mot_de_passe_perdu();
	}
	else
		$controleur->afficher_menu_connexion();
}

else {
	$utilisateur_manager = new UtilisateurManager($bdd->bdd);
	$_SESSION['utilisateur']->droits = $utilisateur_manager->obtenir_droits($_SESSION['utilisateur']->id);

	if (!isset($_GET['section'])) {
		$controleur = new AccueilControleur($bdd->bdd);
		$controleur->afficher_accueil_admin();
	}
	else {
		$section = strtolower($_GET['section']);

		// Forme de l'URL : admin.php?section=politique
		if ($section == 'politique') {
			$controleur = new ArticleControleur($bdd->bdd);

			// Forme de l'URL : admin.php?section=politique&ajouter
			if (isset($_GET['ajouter'])) {
				if ($_SESSION['utilisateur']->droits[Section::TOUT] == Utilisateur::ADMIN || $_SESSION['utilisateur']->droits[Section::POLITIQUE] <= Utilisateur::CONTRIBUTEUR)
					$controleur->ajouter_article(Section::POLITIQUE);
				else
					$controleur->afficher_liste_articles_section(Section::POLITIQUE, 1, true);
			}
			elseif(isset($_POST['supprimer_article']))
				$controleur->afficher_liste_articles_section(Section::POLITIQUE, 1, true);
			// Forme de l'URL : admin.php?section=politique&id=[id]
			elseif (isset($_GET['id'])) {
				// Forme de l'URL : admin.php?section=politique&modifier&id=[id]
				if (isset($_GET['modifier']))
					$controleur->modifier_article($_GET['id'], Section::POLITIQUE);
				else
					$controleur->afficher_article($_GET['id'], Section::POLITIQUE, true);
			}
			// Forme de l'URL : admin.php?section=politique&page=[id]
			elseif (isset($_GET['page']))
				$controleur->afficher_liste_articles_section(Section::POLITIQUE, $_GET['page'], true);
			else
				$controleur->afficher_liste_articles_section(Section::POLITIQUE, 1, true);
		}

		// Forme de l'URL : admin.php?section=voyage
		elseif ($section == 'voyage') {
			$controleur = new ArticleControleur($bdd->bdd);

			// Forme de l'URL : admin.php?section=voyage&ajouter
			if (isset($_GET['ajouter'])) {
				if ($_SESSION['utilisateur']->droits[Section::TOUT] == Utilisateur::ADMIN || $_SESSION['utilisateur']->droits[Section::VOYAGE] <= Utilisateur::CONTRIBUTEUR)
					$controleur->ajouter_article(Section::VOYAGE);
				else
					$controleur->afficher_liste_articles_section(Section::VOYAGE, 1, true);
			}
			elseif(isset($_POST['supprimer_article']))
				$controleur->afficher_liste_articles_section(Section::VOYAGE, 1, true);
			// Forme de l'URL : admin.php?section=voyage&id=[id]
			elseif (isset($_GET['id'])) {
				// Forme de l'URL : admin.php?section=voyage&modifier&id=[id]
				if (isset($_GET['modifier']))
					$controleur->modifier_article($_GET['id'], Section::VOYAGE);
				else
					$controleur->afficher_article($_GET['id'], Section::VOYAGE, true);
			}
			// Forme de l'URL : admin.php?section=voyage&pays=[id]
			elseif (isset($_GET['pays'])) {
				// Forme de l'URL : admin.php?section=voyage&pays=[id]&page=[numero]
				if (isset($_GET['page']))
					$controleur->afficher_liste_articles_pays(Section::VOYAGE, $_GET['page'], true);
				else
					$controleur->afficher_liste_articles_pays(Section::VOYAGE, 1, true);
			}
			// Forme de l'URL : admin.php?section=voyage&page=[numero]
			elseif (isset($_GET['page']))
				$controleur->afficher_liste_articles_section(Section::VOYAGE, $_GET['page'], true);
			else
				$controleur->afficher_liste_articles_section(Section::VOYAGE, 1, true);
		}

		// Forme de l'URL : admin.php?section=pays
		elseif ($section == 'pays') {
			$controleur = new PaysControleur($bdd->bdd);
			$controleur->afficher_pays();
		}

		// Forme de l'URL : admin.php?section=photos
		elseif ($section == 'photos') {
			// Forme de l'URL : admin.php?section=photos&ajouter
			if (isset($_GET['ajouter']) && !isset($_POST['supprimer_photo'])) {
				if ($_SESSION['utilisateur']->droits[Section::TOUT] == Utilisateur::ADMIN || $_SESSION['utilisateur']->droits[Section::PHOTOS] <= Utilisateur::CONTRIBUTEUR) {
					$controleur = new PhotoControleur($bdd->bdd);
					$controleur->ajouter_photo_page();
				}
				else {
					$controleur = new CategoriePhotoControleur($bdd->bdd);
					$controleur->afficher_liste_categories(true);
				}
			}
			elseif (isset($_POST['supprimer_photo'])) {
				if ($_SESSION['utilisateur']->droits[Section::TOUT] == Utilisateur::ADMIN || $_SESSION['utilisateur']->droits[Section::PHOTOS] <= Utilisateur::MODERATEUR) {
					if(isset($_GET['categorie'])) {
						$controleur = new CategoriePhotoControleur($bdd->bdd);
						$controleur->afficher_categorie($_GET['categorie'], true);
					}
					else {
						$controleur = new CategoriePhotoControleur($bdd->bdd);
						$controleur->afficher_liste_categories(true);
					}
				}
				else {
					unset($_POST['supprimer_photo']);
					$controleur = new CategoriePhotoControleur($bdd->bdd);
					$controleur->afficher_liste_categories(true);
				}
			}
			// Forme de l'URL : admin.php?section=photos&id=[id]
			elseif (isset($_GET['id'])) {
				$controleur = new PhotoControleur($bdd->bdd);
				$controleur->afficher_photo($_GET['id'], true);
			}
			// Forme de l'URL : admin.php?section=photos&categorie=[categorie]
			elseif (isset($_GET['categorie'])) {
				$controleur = new CategoriePhotoControleur($bdd->bdd);
				$controleur->afficher_categorie($_GET['categorie'], true);
			}
			else {
				$controleur = new CategoriePhotoControleur($bdd->bdd);
				$controleur->afficher_liste_categories(true);
			}
		}

		// Forme de l'URL : admin.php?section=categories
		elseif ($section == 'categories') {
			if ($_SESSION['utilisateur']->droits[Section::TOUT] == Utilisateur::ADMIN) {
				$controleur = new CategoriePhotoControleur($bdd->bdd);

				// Forme de l'URL : admin.php?section=categories&ajouter
				if (isset($_GET['ajouter']))
					$controleur->ajouter_categorie_photos();
				// Forme de l'URL : admin.php?section=categories&id=[id]
				elseif (isset($_GET['id']) && !isset($_POST['supprimer_categorie']))
					$controleur->afficher_categorie($_GET['id'], true);
				else
					$controleur->afficher_liste_categories(true);
			}
			else {
				$controleur = new AccueilControleur($bdd->bdd);
				$controleur->afficher_accueil_admin();
			}

		}

		// Forme de l'URL : admin.php?section=utilisateur
		elseif ($section == 'utilisateur') {
			if ($_SESSION['utilisateur']->droits[Section::TOUT] == Utilisateur::ADMIN) {
				$controleur = new UtilisateurControleur($bdd->bdd);

				// Forme de l'URL : admin.php?section=utilisateur&ajouter
				if (isset($_GET['ajouter'])) {
					$controleur->ajouter_utilisateur();
				}
				// Forme de l'URL : admin.php?section=utilisateur&id=[id]
				elseif (isset($_GET['id'])) {
					$controleur->afficher_utilisateur($_GET['id']);
				}
				else {
					$controleur->afficher_liste_utilisateurs();
				}
			}
			else {
				$controleur = new AccueilControleur($bdd->bdd);
				$controleur->afficher_accueil_admin();
			}
		}

		// Forme de l'URL : admin.php?section=profil
		elseif ($section == 'profil') {
			$controleur = new UtilisateurControleur($bdd->bdd);
			$controleur->afficher_profil();
		}

		else {
			$controleur = new AccueilControleur($bdd->bdd);
			$controleur->afficher_accueil_admin();
		}
	}
}