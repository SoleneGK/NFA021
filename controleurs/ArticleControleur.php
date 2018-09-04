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
	static function obtenir_numeros_pages($numero_page_actuelle, $nombre_articles) {
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

	function ajouter_article($id_section) {
		// Si un formulaire a été envoyé
		if (isset($_POST['titre_article']) && isset($_POST['contenu_article'])) {
			// Article voyage : si un pays est sélectionné, vérifier qu'il existe
			if ($id_section == Section::VOYAGE && isset($_POST['id_pays']) && $_POST['id_pays'] != -1) {
				$pays_manager = new PaysManager($this->bdd);
				$pays = $pays_manager->obtenir_pays((int)$_POST['id_pays']);

				if (empty($pays)) {
					header('Location: admin.php');
					exit();
				}
			}
			else
				$_POST['pays'] = null;

			$article_manager = new ArticleManager($this->bdd);
			$resultat = $article_manager->ajouter_article($_POST['titre_article'], $id_section, $_POST['contenu_article'], $_SESSION['utilisateur']->id, (int)$_POST['id_pays']);

			if ($resultat) {
				$destination = 'admin.php?section=';
				if ($id_section == Section::POLITIQUE)
					$destination .= 'politique';
				else
					$destination .= 'voyage';
				$destination .= '&id='.$resultat;

				header('Location: '.$destination);
				exit();
			}

			else {
				include 'vues/entete.php';

				if ($id_section == Article::POLITIQUE)
					include 'vues/article/politique/ajouter_article_politique.php';
				else {
					$pays_manager = new PaysManager($this->bdd);
					$liste_pays = $pays_manager->obtenir_liste_pays();
					include 'vues/article/voyage/ajouter_article_voyage.php';
				}

				include 'vues/pieddepage.php';
			}
		}

		else {
			include 'vues/entete.php';

			if ($id_section == Article::POLITIQUE)
				include 'vues/article/politique/ajouter_article_politique.php';
			else {
				$pays_manager = new PaysManager($this->bdd);
				$liste_pays = $pays_manager->obtenir_liste_pays();
				include 'vues/article/voyage/ajouter_article_voyage.php';
			}

			include 'vues/pieddepage.php';
		}

	}

	function afficher_article($id_article, $id_section, $admin = false, $droits_utilisateur = false) {
		$id_article = (int)$id_article;
		$id_section = (int)$id_section;
		$article_manager = new ArticleManager($this->bdd);
		$article = $article_manager->obtenir_article($id_article, $id_section);

		include 'vues/entete.php';

		if (!$article) {
			if ($id_section == Article::POLITIQUE)
				include 'vues/article/politique/aucun_article_politique.php';
			else
				include 'vues/article/voyage/aucun_article_voyage.php';
		}
		else {
			// Obtenir les infos sur les articles suivants et précédents
			$article_suivant = $article_manager->obtenir_article_suivant($id_article, $id_section);
			$article_precedent = $article_manager->obtenir_article_precedent($id_article, $id_section);

			if ($id_section == Article::POLITIQUE)
				include 'vues/article/politique/afficher_article_politique.php';
			else
				include 'vues/article/voyage/afficher_article_voyage.php';

			$commentaire_manager = new CommentaireManager($this->bdd);

			// Ajout d'un commentaire
			if (isset($_POST['ajouter_commentaire']) && isset($_POST['pseudo']) && isset($_POST['mail']) && isset($_POST['contenu'])) {
				$commentaire_manager->ajouter_commentaire(null, $_POST['pseudo'], $_POST['mail'], $article->id, $_POST['contenu']);
			}

			$commentaires = $commentaire_manager->obtenir_commentaires_article($article->id);

			if (!$commentaires)
				include 'vues/commentaires/aucun_commentaire.php';
			else
				include 'vues/commentaires/afficher_commentaires.php';

			include 'vues/commentaires/formulaire_ajouter_commentaire.php';
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
			$numeros_pages = self::obtenir_numeros_pages($page, $article_manager->nombre_articles_section($id_section));
		}

		if ($id_section == Article::POLITIQUE)
			include 'vues/article/politique/afficher_liste_articles_politique.php';
		else
			include 'vues/article/voyage/afficher_liste_articles_voyage.php';

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
				$numeros_pages = self::obtenir_numeros_pages($page, $article_manager->nombre_articles_pays($id_pays));
			}

			include 'vues/article/voyage/afficher_liste_articles_pays.php';
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
				$numeros_pages = self::obtenir_numeros_pages($page, $article_manager->nombre_articles_utilisateur($utilisateur));
			}

			include 'vues/article/afficher_liste_articles_utilisateur.php';

		}

		include 'vues/pieddepage.php';
	}

	function modifier_article($id_article, $id_section, $droits_utilisateur) {
		$id_article = (int)$id_article;
		$id_section = (int)$id_section;

		$article_manager = new ArticleManager($this->bdd);
		$article = $article_manager->obtenir_article($id_article, $id_section);

		// Vérifier qu'un article a été trouvé
		if ($article) {
			var_dump($article);
			// Vérifier que l'utilisateur a le droit de modifier l'article
			if ($droits_utilisateur[Section::TOUT] == Utilisateur::ADMIN || $droits_utilisateur[Section::POLITIQUE] <= Utilisateur::MODERATEUR || ($droits_utilisateur[Section::POLITIQUE] <= Utilisateur::CONTRIBUTEUR && $article->utilisateur->id == $_SESSION['utilisateur']->id)) {
				
				// Si un formulaire a été envoyé
				if (isset($_POST['titre_article']) && isset($_POST['contenu_article'])) {
					// Article voyage : si un pays est sélectionné, vérifier qu'il existe
					if ($id_section == Section::VOYAGE && isset($_POST['id_pays']) && $_POST['id_pays'] != -1) {
						$pays_manager = new PaysManager($this->bdd);
						$pays = $pays_manager->obtenir_pays((int)$_POST['id_pays']);

						if (empty($pays)) {
							header('Location: admin.php');
							exit();
						}
					}
					else
						$_POST['pays'] = null;

					$resultat = $article_manager->modifier_article($id_article, $_POST['titre_article'], $_POST['contenu_article'], (int)$_POST['id_pays']);

					// Affichage de l'article si réussite de modification en bdd, affichage du formulaire sinon
					if(false) {
						$destination = 'admin.php?section=';
						if ($id_section == Section::POLITIQUE)
							$destination .= 'politique';
						else
							$destination .= 'voyage';
						$destination .= '&id='.$id_article;

						header('Location: '.$destination);
						exit();
					}

					else {
						// Affichage de l'article avec modifications
						$article->titre = $_POST['titre_article'];
						$article->contenu = $_POST['contenu_article'];
						$article->pays = new Pays($_POST['id_pays'], null);

						include 'vues/entete.php';

						if ($id_section == Article::POLITIQUE)
							include 'vues/article/politique/modifier_article_politique.php';
						else {
							$pays_manager = new PaysManager($this->bdd);
							$liste_pays = $pays_manager->obtenir_liste_pays();
							include 'vues/article/voyage/modifier_article_voyage.php';
						}

						include 'vues/pieddepage.php';
					}
				}

				else {
					include 'vues/entete.php';

					if ($id_section == Article::POLITIQUE)
						include 'vues/article/politique/modifier_article_politique.php';
					else {
						$pays_manager = new PaysManager($this->bdd);
						$liste_pays = $pays_manager->obtenir_liste_pays();
						include 'vues/article/voyage/modifier_article_voyage.php';
					}

					include 'vues/pieddepage.php';
				}
			}

			else {
				header('Location: admin.php');
				exit();
			}
		}

		else {
			header('Location: admin.php');
			exit();
		}
		
	}
}