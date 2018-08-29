<?php

class ArticleControleur {
	private $bdd;

	function __construct(PDO $bdd) {
		$this->bdd = $bdd;
	}

	function afficher_article($id_article, $id_section) {
		$article_manager = new ArticleManager($this->bdd);
		$article = $article_manager->obtenir_article((int)$id_article);

		include 'vues/entete.php';

		if (!$article) {
			if ($id_section == Article::POLITIQUE)
				include 'vues/article/aucun_article_politique.php';
			else
				include 'vues/article/aucun_article_voyage.php';
		}
		else {
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
		}

		if ($id_section == Article::POLITIQUE)
			include 'vues/article/afficher_liste_articles_politique.php';
		else
			include 'vues/article/afficher_liste_articles_voyage.php';

		include 'vues/pieddepage.php';
	}

	function afficher_liste_articles_pays($id_pays, $page = 1) {
		include 'vues/entete.php';

		$page = (int)$page;
		if ($page <= 0)
			$articles = [];
		else {
			// Obtenir la liste des articles
			$article_manager = new ArticleManager($this->bdd);
			// Position du 1er article = (n° page - 1) × nombre d'articles par page
			$articles = $article_manager->obtenir_articles_pays($id_pays, ($page - 1) * NOMBRE_ARTICLES_PAR_PAGE);
		}

		include 'vues/article/afficher_liste_articles_pays.php';

		include 'vues/pieddepage.php';
	}
}