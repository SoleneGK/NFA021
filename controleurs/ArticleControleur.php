<?php

class ArticleControleur {
	private $bdd;

	function __construct(PDO $bdd) {
		$this->bdd = $bdd;
	}

	/* Obtenir les numéros des pages pour la navigation dans la liste des articles
	 * Renvoie un array associatif
	 * premiere_page => 1
	 * page_precedente => null si la page actuelle est la 1e, ou le numéro de la page
	 * page_suivante => null si la page actuelle est la dernière, ou le numéro de la page
	 * derniere_page => le numéro de la page
	 */
	function obtenir_numeros_pages($numero_page_actuelle, $nombre_articles) {
		$numeros['premiere_page'] = 1;
		
		if ($numero_page_actuelle == 1)
			$numeros['page_precedente'] = null;
		else
			$numeros['page_precedente'] = $numero_page_actuelle - 1;

		$numeros['derniere_page'] = ceil($nombre_articles / NOMBRE_ARTICLES_PAR_PAGE);

		if ($numero_page_actuelle < $numeros['derniere_page'])
			$numeros['page_suivante'] = $numero_page_actuelle + 1;
		else
			$numeros['page_suivante'] = null;

		return $numeros;
	}

	function afficher_article($id_article, $id_section) {
		$id_article = (int)$id_article;
		$id_section = (int)$id_section;
		$article_manager = new ArticleManager($this->bdd);
		$article = $article_manager->obtenir_article($id_article, $id_section);

		include 'vues/entete.php';

		if (!$article) {
			if ($id_section == Article::POLITIQUE)
				include 'vues/article/aucun_article_politique.php';
			else
				include 'vues/article/aucun_article_voyage.php';
		}
		else {
			// Obtenir les infos sur les articles suivants et précédents
			$article_suivant = $article_manager->obtenir_article_suivant($id_article, $id_section);
			$article_precedent = $article_manager->obtenir_article_precedent($id_article, $id_section);

			if ($id_section == Article::POLITIQUE)
				include 'vues/article/afficher_article_politique.php';
			else
				include 'vues/article/afficher_article_voyage.php';
		}

		include 'vues/pieddepage.php';
	}

	function afficher_liste_articles_section($id_section, $page = 1) {
		include 'vues/entete.php';

		$page = (int)$page;
		if ($page <= 0)
			$articles = [];
		else {
			// Obtenir la liste des articles
			$article_manager = new ArticleManager($this->bdd);
			// Position du 1er article = (n° page - 1) × nombre d'articles par page
			$articles = $article_manager->obtenir_articles_section($id_section, ($page - 1) * NOMBRE_ARTICLES_PAR_PAGE);

			// Obtenir les numéros de page pour la navigation
			$numeros_pages = $this->obtenir_numeros_pages($page, $article_manager->nombre_articles_section($id_section));
		}

		if ($id_section == Article::POLITIQUE)
			include 'vues/article/afficher_liste_articles_politique.php';
		else
			include 'vues/article/afficher_liste_articles_voyage.php';

		include 'vues/pieddepage.php';
	}

	function afficher_liste_articles_pays($id_pays, $page = 1) {
		include 'vues/entete.php';

		// Vérifier que le pays existe
		$pays_manager = new PaysManager($this->bdd);
		$pays = $pays_manager->obtenir_pays((int)$id_pays);

		if ($pays) {
			$page = (int)$page;
			if ($page <= 0)
				$articles = [];
			else {
				// Obtenir la liste des articles
				$article_manager = new ArticleManager($this->bdd);
				// Position du 1er article = (n° page - 1) × nombre d'articles par page
				$articles = $article_manager->obtenir_articles_pays($pays, ($page - 1) * NOMBRE_ARTICLES_PAR_PAGE);

				// Obtenir les numéros de page pour la navigation
				$numeros_pages = $this->obtenir_numeros_pages($page, $article_manager->nombre_articles_pays($id_pays));
			}

			include 'vues/article/afficher_liste_articles_pays.php';
		}
		else
			include 'vues/pays/aucun_pays.php';

		include 'vues/pieddepage.php';
	}

	// Afficher la liste des articles publiés par un utilisateur
	function afficher_liste_articles_utilisateur($id_utilisateur, $page = 1) {
		// Vérifier que l'utilisateur existe
		$utilisateur_manager = new UtilisateurManager($this->bdd);
		$utilisateur = $utilisateur_manager->obtenir_utilisateur($id_utilisateur);

		include 'vues/entete.php';

		if(!$utilisateur)
			include 'vues/utilisateur/aucun_utilisateur.php';
		else {
			// Obtenir la liste des articles
			$page = (int)$page;
			if ($page <= 0)
				$articles = [];
			else {
				// Obtenir la liste des articles
				$article_manager = new ArticleManager($this->bdd);
				// Position du 1er article = (n° page - 1) × nombre d'articles par page
				$articles = $article_manager->obtenir_articles_utilisateur($utilisateur, ($page - 1) * NOMBRE_ARTICLES_PAR_PAGE);

				// Obtenir les numéros de page pour la navigation
				$numeros_pages = $this->obtenir_numeros_pages($page, $article_manager->nombre_articles_utilisateur($utilisateur));
			}

			include 'vues/article/afficher_liste_articles_utilisateur.php';

		}

		include 'vues/pieddepage.php';
	}
}