<?php

class UtilisateurControleur {
	private $bdd;

	function __construct(PDO $bdd) {
		$this->bdd = $bdd;
	}

	// Afficher la liste des articles publiés par un utilisateur
	function afficher_articles ($id_utilisateur, $page = 1) {
		// Vérifier que l'utilisateur existe
		$utilisateurManager = new UtilisateurManager($this->bdd);
		$utilisateur = $utilisateurManager->obtenir_utilisateur($id_utilisateur);

		include 'vues/entete.php';

		if(!$utilisateur)
			include 'vues/utilisateur/aucun_utilisateur.php';
		else {
			// Obtenir la liste des articles
			if ((int)$page <= 0)
				$articles = [];
			else {
				// Obtenir la liste des articles
				$articlesManager = new ArticleManager($this->bdd);
				// Position du 1er article = (n° page - 1) × nombre d'articles par page
				$articles = $articlesManager->afficher_articles_utilisateur($utilisateur, ((int)$page - 1) * NOMBRE_ARTICLES_PAR_PAGE);
			}

			include 'vues/utilisateur/liste_articles.php';

		}

		include 'vues/pieddepage.php';
	}
}