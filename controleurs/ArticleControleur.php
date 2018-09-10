<?php

class ArticleControleur {
	private $bdd;

	function __construct(PDO $bdd) {
		$this->bdd = $bdd;
	}

	function ajouter_article($id_section) {
		$categorie_manager = new CategoriePhotoManager($this->bdd);
		$categories = $categorie_manager->obtenir_liste();

		$pays_manager = new PaysManager($this->bdd);
		$pays = $pays_manager->obtenir_liste_pays();

		// Si un formulaire a été envoyé
		if (isset($_POST['titre_article']) && isset($_POST['contenu_article'])) {
			// Article voyage : si un pays est sélectionné, vérifier qu'il existe
			if ($id_section == Section::VOYAGE && isset($_POST['id_pays']) && $_POST['id_pays'] != -1) {
				$pays_bdd = $pays_manager->obtenir_pays((int)$_POST['id_pays']);

				if (empty($pays_bdd)) {
					header('Location: admin.php');
					exit();
				}
			}
			else
				$_POST['pays'] = null;

			// Ajout de l'article en bdd
			$article_manager = new ArticleManager($this->bdd);
			$resultat = $article_manager->ajouter_article(trim($_POST['titre_article']), $id_section, trim($_POST['contenu_article']), $_SESSION['utilisateur']->id, (int)$_POST['id_pays']);

			// Envoi sur la page de l'article
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

			// Affichage de la page de saisie en conservant ce qui a été entré
			else {
				include 'vues/entete.php';
				include 'vues/menu_admin.php';

				if ($id_section == Article::POLITIQUE)
					include 'vues/article/politique/ajouter_article_politique.php';
				else
					include 'vues/article/voyage/ajouter_article_voyage.php';

				include 'vues/pieddepage.php';
			}
		}

		// Affichage du formulaire de saisie
		else {
			include 'vues/entete.php';
			include 'vues/menu_admin.php';

			if ($id_section == Article::POLITIQUE)
				include 'vues/article/politique/ajouter_article_politique.php';
			else
				include 'vues/article/voyage/ajouter_article_voyage.php';

			include 'vues/pieddepage.php';
		}

	}

	function afficher_article($id_article, $id_section, $admin = false) {
		$id_article = (int)$id_article;
		$id_section = (int)$id_section;

		include 'vues/entete.php';

		$categorie_manager = new CategoriePhotoManager($this->bdd);
		$categories = $categorie_manager->obtenir_liste();

		$pays_manager = new PaysManager($this->bdd);
		$pays = $pays_manager->obtenir_liste_pays();

		if ($admin)
			include 'vues/menu_admin.php';
		else
			include 'vues/menu.php';

		$article_manager = new ArticleManager($this->bdd);
		$article = $article_manager->obtenir_article($id_article, $id_section);

		if (!$article) {
			if ($id_section == Article::POLITIQUE)
				include 'vues/article/politique/afficher_article_politique.php';
			else
				include 'vues/article/voyage/afficher_article_voyage.php';
		}
		else {
			// Obtenir les infos sur les articles suivants et précédents
			$article_suivant = $article_manager->obtenir_article_suivant($id_article, $id_section);
			$article_precedent = $article_manager->obtenir_article_precedent($id_article, $id_section);

			// Affichage de l'article
			if ($id_section == Article::POLITIQUE)
				include 'vues/article/politique/afficher_article_politique.php';
			else
				include 'vues/article/voyage/afficher_article_voyage.php';

			$commentaire_manager = new CommentaireManager($this->bdd);

			// Ajout d'un commentaire
			if (isset($_SESSION['utilisateur'])) {
				if (isset($_POST['ajouter_commentaire']) && isset($_POST['contenu']))
					$commentaire_manager->ajouter_commentaire($_SESSION['utilisateur']->id, null, null, $article->id, trim($_POST['contenu']));
			}
			else {
				if (isset($_POST['ajouter_commentaire']) && isset($_POST['pseudo']) && isset($_POST['mail']) && isset($_POST['contenu']))
					$commentaire_manager->ajouter_commentaire(null, trim($_POST['pseudo']), $_POST['mail'], $article->id, trim($_POST['contenu']));
			}

			$commentaires = $commentaire_manager->obtenir_commentaires_article($article->id);

			// Affichage des commentaires
				include 'vues/commentaires/afficher_commentaires.php';

			// Formulaire d'ajout de commentaire
			include 'vues/commentaires/formulaire_ajouter_commentaire.php';
		}

		include 'vues/pieddepage.php';
	}

	function afficher_liste_articles_section($id_section, $page = 1, $admin = false) {
		include 'vues/entete.php';

		$categorie_manager = new CategoriePhotoManager($this->bdd);
		$categories = $categorie_manager->obtenir_liste();

		$pays_manager = new PaysManager($this->bdd);
		$pays = $pays_manager->obtenir_liste_pays();

		if ($admin)
			include 'vues/menu_admin.php';
		else
			include 'vues/menu.php';


		$page = (int)$page;
		if ($page <= 0)
			$articles = [];
		else {
			$article_manager = new ArticleManager($this->bdd);

			// Suppression d'un article
			if (isset($_POST['supprimer_article']) && isset($_POST['id_article'])) {
				if ($_SESSION['utilisateur']->droits[Section::TOUT] == Utilisateur::ADMIN || ($id_section == Section::POLITIQUE && $_SESSION['utilisateur']->droits[Section::POLITIQUE] <= Utilisateur::MODERATEUR) || ($id_section == Section::VOYAGE && $_SESSION['utilisateur']->droits[Section::VOYAGE] <= Utilisateur::MODERATEUR)) {
					$commentaire_manager = new CommentaireManager($this->bdd);
					$commentaire_manager->supprimer_commentaires_article((int)$_POST['id_article']);

					$article_manager->supprimer_article((int)$_POST['id_article']);
				}
			}


			// Position du 1er article = (n° page - 1) × nombre d'articles par page
			$articles = $article_manager->obtenir_articles_section($id_section, ($page - 1) * NOMBRE_ARTICLES_PAR_PAGE);

			// Obtenir les numéros de page pour la navigation
			require_once 'outils/numeros_pages.php';
			$numeros_pages = obtenir_numeros_pages($page, $article_manager->nombre_articles_section($id_section), NOMBRE_ARTICLES_PAR_PAGE);
		}

		if ($id_section == Article::POLITIQUE)
			include 'vues/article/politique/afficher_liste_articles_politique.php';
		else
			include 'vues/article/voyage/afficher_liste_articles_voyage.php';

		include 'vues/pieddepage.php';
	}

	function afficher_liste_articles_pays($id_pays, $page = 1, $admin = false) {
		include 'vues/entete.php';

		$categorie_manager = new CategoriePhotoManager($this->bdd);
		$categories = $categorie_manager->obtenir_liste();

		$pays_manager = new PaysManager($this->bdd);
		$pays = $pays_manager->obtenir_liste_pays();

		if ($admin)
			include 'vues/menu_admin.php';
		else
			include 'vues/menu.php';

		// Vérifier que le pays existe
		$pays_selectionne = $pays_manager->obtenir_pays((int)$id_pays);

		if ($pays_selectionne) {
			$page = (int)$page;
			if ($page <= 0)
				$articles = [];
			else {
				// Obtenir la liste des articles
				$article_manager = new ArticleManager($this->bdd);
				// Position du 1er article = (n° page - 1) × nombre d'articles par page
				$articles = $article_manager->obtenir_articles_pays($pays_selectionne, ($page - 1) * NOMBRE_ARTICLES_PAR_PAGE);

				// Obtenir les numéros de page pour la navigation
				require_once 'outils/numeros_pages.php';
				$numeros_pages = obtenir_numeros_pages($page, $article_manager->nombre_articles_section($id_section), NOMBRE_ARTICLES_PAR_PAGE);
			}

			include 'vues/article/voyage/afficher_liste_articles_pays.php';
		}
		else
			include 'vues/pays/aucun_pays.php';

		include 'vues/pieddepage.php';
	}

	// Afficher la liste des articles publiés par un utilisateur
	function afficher_liste_articles_utilisateur($id_utilisateur, $page = 1, $admin = false) {
		// Vérifier que l'utilisateur existe
		$utilisateur_manager = new UtilisateurManager($this->bdd);
		$utilisateur = $utilisateur_manager->obtenir_utilisateur($id_utilisateur);

		include 'vues/entete.php';

		$categorie_manager = new CategoriePhotoManager($this->bdd);
		$categories = $categorie_manager->obtenir_liste();

		$pays_manager = new PaysManager($this->bdd);
		$pays = $pays_manager->obtenir_liste_pays();

		include 'vues/menu.php';

		if ($utilisateur) {
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
				require_once 'outils/numeros_pages.php';
				$numeros_pages = obtenir_numeros_pages($page, $article_manager->nombre_articles_section($id_section), NOMBRE_ARTICLES_PAR_PAGE);
			}
		}

		include 'vues/article/afficher_liste_articles_utilisateur.php';
		include 'vues/pieddepage.php';
	}

	function modifier_article($id_article, $id_section) {
		$id_article = (int)$id_article;
		$id_section = (int)$id_section;

		$article_manager = new ArticleManager($this->bdd);
		$article = $article_manager->obtenir_article($id_article, $id_section);

		// Vérifier qu'un article a été trouvé
		if ($article) {
			// Vérifier que l'utilisateur a le droit de modifier l'article
			if ($_SESSION['utilisateur']->droits[Section::TOUT] == Utilisateur::ADMIN || $_SESSION['utilisateur']->droits[Section::POLITIQUE] <= Utilisateur::MODERATEUR || ($_SESSION['utilisateur']->droits[Section::POLITIQUE] <= Utilisateur::CONTRIBUTEUR && $article->utilisateur->id == $_SESSION['utilisateur']->id)) {
				
				$categorie_manager = new CategoriePhotoManager($this->bdd);
				$categories = $categorie_manager->obtenir_liste();

				$pays_manager = new PaysManager($this->bdd);
				$pays = $pays_manager->obtenir_liste_pays();

				// Si un formulaire a été envoyé
				if (isset($_POST['titre_article']) && isset($_POST['contenu_article'])) {
					// Article voyage : si un pays est sélectionné, vérifier qu'il existe
					if ($id_section == Section::VOYAGE && isset($_POST['id_pays']) && $_POST['id_pays'] != -1) {
						$pays = $pays_manager->obtenir_pays((int)$_POST['id_pays']);

						if (empty($pays)) {
							header('Location: admin.php');
							exit();
						}
					}
					else
						$_POST['id_pays'] = null;

					$resultat = $article_manager->modifier_article($id_article, trim($_POST['titre_article']), trim($_POST['contenu_article']), (int)$_POST['id_pays']);

					// Affichage de l'article si réussite de modification en bdd, affichage du formulaire sinon
					if($resultat) {
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
						include 'vues/menu_admin.php';

						if ($id_section == Article::POLITIQUE)
							include 'vues/article/politique/modifier_article_politique.php';
						else
							include 'vues/article/voyage/modifier_article_voyage.php';

						include 'vues/pieddepage.php';
					}
				}

				else {
					include 'vues/entete.php';
					include 'vues/menu_admin.php';

					if ($id_section == Article::POLITIQUE)
						include 'vues/article/politique/modifier_article_politique.php';
					else
						include 'vues/article/voyage/modifier_article_voyage.php';

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