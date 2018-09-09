<?php
require_once 'outils/afficher.php';
require_once 'outils/Autoloader.php';
Autoloader::enregistrer();

session_start();

$bdd = new Bdd();

define('NOMBRE_ARTICLES_PAR_PAGE', 5);

if (!isset($_GET['section'])) {
	$controleur = new AccueilControleur($bdd->bdd);
	$controleur->index();
}
else {
	$section = strtolower($_GET['section']);

	if ($section == 'photos') {
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

	elseif ($section == 'politique') {
		/* Formes de l'URL :
		 * index.php?section=politique
		 * index.php?section=politique&page=[numero]
		 * index.php?section=politique&id=[id]
		 */

		$controleur = new ArticleControleur($bdd->bdd);

		if (isset($_GET['page']))
			$controleur->afficher_liste_articles_section(Article::POLITIQUE, $_GET['page']);
		elseif (isset($_GET['id']))
			$controleur->afficher_article($_GET['id'], Article::POLITIQUE);
		else
			$controleur->afficher_liste_articles_section(Article::POLITIQUE);

	}

	elseif ($section == 'voyage') {
		/* Formes de l'URL :
		 * index.php?section=voyage
		 * index.php?section=voyage&page=[numero]
		 * index.php?section=voyage&id=[id]
		 * index.php?section=voyage&pays=[id]
		 * index.php?section=voyage&pays=[id]&page=[numero]
		 */

		$controleur = new ArticleControleur($bdd->bdd);

		if (isset($_GET['id']))
			$controleur->afficher_article($_GET['id'], Article::VOYAGE);
		elseif (isset($_GET['pays'])) {
			if (isset($_GET['page']))
				$controleur->afficher_liste_articles_pays($_GET['pays'], $_GET['page']);
			else
				$controleur->afficher_liste_articles_pays($_GET['pays']);
		}
		elseif (isset($_GET['page']))
			$controleur->afficher_liste_articles_section(Article::VOYAGE, $_GET['page']);
		else
			$controleur->afficher_liste_articles_section(Article::VOYAGE);
	}

	elseif ($section == 'utilisateur') {
		/* Formes de l'URL :
		 * index.php?section=utilisateur&id=[id]
		 * index.php?section=utilisateur&id=[id]&page=[numero]
		 */

		if (isset($_GET['id'])) {
			$controleur = new ArticleControleur($bdd->bdd);

			if (isset($_GET['page']))
				$controleur->afficher_liste_articles_utilisateur($_GET['id'], $_GET['page']);
			else
				$controleur->afficher_liste_articles_utilisateur($_GET['id']);
		}
		else {
			$controleur = new AccueilControleur($bdd->bdd);
			$controleur->index();
		}

	}

	else {
		$accueil = new AccueilControleur($bdd->bdd);
		$accueil->index();
	}
}


