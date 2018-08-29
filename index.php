<?php
require_once 'outils/Autoloader.php';
Autoloader::enregistrer();

session_start();

$bdd = new Bdd();

define('NOMBRE_ARTICLES_PAR_PAGE', 5);

if (!isset($_GET['section'])) {
	$controleur = new AccueilControleur();
	$controleur->index();
}
else {
	if ($_GET['section'] == 'photos') {
		/* Forme de l'URL :
		 * index.php?section=photos
		 * index.php?section=photos&categorie=[id]
		 * index.php?section=photos&id=[id]
		 */

		if (isset($_GET['categorie'])) {
			$controleur = new PhotoControleur($bdd->bdd);
			$controleur->afficher_categorie($_GET['categorie']);
		}
		elseif (isset($_GET['id'])) {
			$controleur = new PhotoControleur($bdd->bdd);
			$controleur->afficher_photo($_GET['id']);
		}
		else {
			$controleur = new CategoriePhotoControleur($bdd->bdd);
			$controleur->afficher_liste();
		}
	}
	elseif ($_GET['section'] == 'politique') {
		echo $_GET['section'];
	}
	elseif ($_GET['section'] == 'voyages') {
		echo $_GET['section'];
	}
	elseif ($_GET['section'] == 'utilisateur') {
		/* Forme de l'URL :
		 * index.php?section=utilisateur&id=[id]
		 * index.php?section=utilisateur&id=[id]&page=[numero]
		 */

		if (isset($_GET['id'])) {
			$controleur = new UtilisateurControleur($bdd->$bdd);

			if (isset($_GET['page']))
				$controleur->afficher_articles($_GET['id'], $_GET['page']);
			else
				$controleur->afficher_articles($_GET['id']);
		}
		else {
			$controleur = new AccueilControleur();
			$controleur->index();
		}

	}
	else {
		$accueil = new AccueilControleur();
		$accueil->index();
	}
}


