<?php

class ArticleManager {
	private $bdd;

	function __construct(PDO $bdd) {
		$this->bdd = $bdd;
	}

	/* Obtenir les informations d'un article
	 * Renvoie un objet de type Article s'il existe
	 * Renvoie false sinon
	 */
	function obtenir_article($id, $id_section = null) {
		$req = 'SELECT a.titre AS titre,
					a.contenu AS contenu,
					a.date_publication AS date_publication,
					u.id AS id_utilisateur,
					u.pseudo AS pseudo_utilisateur,
					s.id AS id_section,
					s.nom AS nom_section,
					p.id AS id_pays,
					p.nom AS nom_pays
				FROM articles AS a
				JOIN utilisateurs AS u ON a.id_utilisateur = u.id
				JOIN sections_site AS s ON a.id_section = s.id
				LEFT JOIN pays AS p ON a.id_pays = p.id
				WHERE a.id = :id';
		if ($id_section == Article::POLITIQUE || $id_section == Article::VOYAGE)
			$req .= ' AND s.id = :id_section';

		$req = $this->bdd->prepare($req);
		$req->bindValue('id', $id, PDO::PARAM_INT);
		if ($id_section == Article::POLITIQUE || $id_section == Article::VOYAGE)
			$req->bindValue('id_section', $id_section, PDO::PARAM_INT);
		$req->execute();
		$req = $req->fetch(PDO::FETCH_ASSOC);

		// Vérifier qu'un article a été trouvé
		if (!$req)
			$article = false;
		else {
			$u = new Utilisateur($req['id_utilisateur'], $req['pseudo_utilisateur'], null);
			$s = new Section($req['id_section'], $req['nom_section']);

			if (empty($req['id_pays']))
				$p = null;
			else
				$p = new Pays($req['id_pays'], $req['nom_pays']);

			$article = new Article($id, $req['titre'], $s, $req['contenu'], $req['date_publication'], $u, $p);
		}

		return $article;
	}

	/* Obtenir les informations sur l'article suivant celui identifié par l'id, et qui appartient à la même section
	 * Renvoie un objet Article s'il existe
	 * Renvoie false sinon
	 */
	function obtenir_article_suivant($id, $id_section) {
		$req = 'SELECT id, titre FROM articles WHERE id > :id AND id_section = :id_section ORDER BY id LIMIT 1';
		$req = $this->bdd->prepare($req);
		$req->bindValue('id', $id, PDO::PARAM_INT);
		$req->bindValue('id_section', $id_section, PDO::PARAM_INT);
		$req->execute();
		$req = $req->fetch(PDO::FETCH_ASSOC);

		if (!$req)
			$article = false;
		else
			$article = new Article($req['id'], $req['titre'], $id_section);

		return $article;
	}

	/* Obtenir les informations sur l'article précédent celui identifié par l'id, et qui appartient à la même section
	 * Renvoie un objet Article s'il existe
	 * Renvoie false sinon
	 */
	function obtenir_article_precedent($id, $id_section) {
		$req = 'SELECT id, titre FROM articles WHERE id < :id AND id_section = :id_section ORDER BY id DESC LIMIT 1';
		$req = $this->bdd->prepare($req);
		$req->bindValue('id', $id, PDO::PARAM_INT);
		$req->bindValue('id_section', $id_section, PDO::PARAM_INT);
		$req->execute();
		$req = $req->fetch(PDO::FETCH_ASSOC);

		if (!$req)
			$article = false;
		else
			$article = new Article($req['id'], $req['titre'], $id_section);

		return $article;
	}

	/* Obtenir la liste des articles d'un utilisateur
	 * Affiche NOMBRE_ARTICLES_PAR_PAGE à partir du n° $position
	 * Renvoie un array de Article
	 */
	function obtenir_articles_utilisateur(Utilisateur $utilisateur, $position) {
		$req = 'SELECT a.id AS id,
						a.titre AS titre,
						a.contenu AS contenu,
						a.date_publication AS date_publication,
						s.id AS id_section,
						s.nom AS nom_section,
						p.id AS id_pays,
						p.nom AS nom_pays
					FROM articles AS a
					JOIN utilisateurs AS u ON a.id_utilisateur = u.id
					JOIN sections_site AS s ON a.id_section = s.id
					LEFT JOIN pays AS p ON a.id_pays = p.id
					WHERE u.id = :id_utilisateur
					ORDER BY a.id DESC
					LIMIT :position, :nombre';
		$req = $this->bdd->prepare($req);
		$req->bindValue('id_utilisateur', $utilisateur->id, PDO::PARAM_INT);
		$req->bindValue('position', $position, PDO::PARAM_INT);
		$req->bindValue('nombre', NOMBRE_ARTICLES_PAR_PAGE, PDO::PARAM_INT);
		$req->execute();

		$articles = [];
			foreach ($req->fetchAll(PDO::FETCH_ASSOC) as $a) {
				$s = new Section($a['id_section'], $a['nom_section']);
				$p = new Pays($a['id_pays'], $a['nom_pays']);
				$article = new Article($a['id'], $a['titre'], $s, $a['contenu'], $a['date_publication'], $utilisateur, $p);

				$articles[] = $article;
			}

		return $articles;
	}

	/* Obtenir le nombre d'articles d'un utilisateur
	 * Renvoie un entier
	 */
	function nombre_articles_utilisateur(Utilisateur $utilisateur) {
		$req = 'SELECT COUNT(id) FROM articles WHERE id_utilisateur = :id_utilisateur';
		$req = $this->bdd->prepare($req);
		$req->bindValue('id_utilisateur', $utilisateur->id, PDO::PARAM_INT);
		$req->execute();
		$req = $req->fetch(PDO::FETCH_NUM);
		return $req[0];
	}

	/* Obtenir la liste des articles d'une section
	 * Affiche NOMBRE_ARTICLES_PAR_PAGE à partir du n° $position
	 * Renvoie un array de Article
	 */
	function obtenir_articles_section($id_section, $position) {
		$req = 'SELECT a.id AS id,
					a.titre AS titre,
					a.contenu AS contenu,
					a.date_publication AS date_publication,
					u.id AS id_utilisateur,
					u.pseudo AS pseudo_utilisateur,
					s.nom AS nom_section,
					p.id AS id_pays,
					p.nom AS nom_pays
				FROM articles AS a
				JOIN utilisateurs AS u ON a.id_utilisateur = u.id
				JOIN sections_site AS s ON a.id_section = s.id
				LEFT JOIN pays AS p ON a.id_pays = p.id
				WHERE s.id = :id_section
				ORDER BY a.id DESC
				LIMIT :position, :nombre';
		$req = $this->bdd->prepare($req);
		$req->bindValue('id_section', $id_section, PDO::PARAM_INT);
		$req->bindValue('position', $position, PDO::PARAM_INT);
		$req->bindValue('nombre', NOMBRE_ARTICLES_PAR_PAGE, PDO::PARAM_INT);
		$req->execute();

		$articles = [];
		foreach ($req->fetchAll(PDO::FETCH_ASSOC) as $a) {
			$u = new Utilisateur($a['id_utilisateur'], $a['pseudo_utilisateur'], null);
			$s = new Section($id_section, $a['nom_section']);
			$p = new Pays($a['id_pays'], $a['nom_pays']);
			$article = new Article($a['id'], $a['titre'], $s, $a['contenu'], $a['date_publication'], $u, $p);

			$articles[] = $article;
		}

		return $articles;
	}

	/* Obtenir le nonbre d'articles d'une section
	 * Renvoie un entier
	 */
	function nombre_articles_section($id_section) {
		$req = 'SELECT COUNT(id) FROM articles WHERE id_section = :id_section';
		$req = $this->bdd->prepare($req);
		$req->bindValue('id_section', $id_section, PDO::PARAM_INT);
		$req->execute();
		$req = $req->fetch(PDO::FETCH_NUM);
		return $req[0];
	}

	/* Obtenir la liste des articles d'un pays
	 * Affiche NOMBRE_ARTICLES_PAR_PAGE à partir du n° $position
	 * Renvoie un array de Article
	 */
	function obtenir_articles_pays(Pays $pays, $position) {
		var_dump($pays->id);
		$req = 'SELECT a.id AS id,
					a.titre AS titre,
					a.contenu AS contenu,
					a.date_publication AS date_publication,
					u.id AS id_utilisateur,
					u.pseudo AS pseudo_utilisateur 
				FROM articles AS a
				JOIN utilisateurs AS u ON a.id_utilisateur = u.id
				JOIN pays AS p ON a.id_pays = p.id
				WHERE p.id = :id_pays
				ORDER BY a.id DESC
				LIMIT :position, :nombre';
		$req = $this->bdd->prepare($req);
		$req->bindValue('id_pays', $pays->id, PDO::PARAM_INT);
		$req->bindValue('position', $position, PDO::PARAM_INT);
		$req->bindValue('nombre', NOMBRE_ARTICLES_PAR_PAGE, PDO::PARAM_INT);
		$req->execute();

		$articles = [];
		foreach ($req->fetchAll(PDO::FETCH_ASSOC) as $a) {
			$u = new Utilisateur($a['id_utilisateur'], $a['pseudo_utilisateur'], null);
			$s = new Section(Article::VOYAGE, 'Voyage');
			$article = new Article($a['id'], $a['titre'], $s, $a['contenu'], $a['date_publication'], $u, $pays);

			$articles[] = $article;
		}

		return $articles;
	}

	/* Obtenir le nonbre d'articles d'un pays
	 * Renvoie un entier
	 */
	function nombre_articles_pays($id_pays) {
		$req = 'SELECT COUNT(id) FROM articles WHERE id_pays = :id_pays';
		$req = $this->bdd->prepare($req);
		$req->bindValue('id_pays', $id_pays, PDO::PARAM_INT);
		$req->execute();
		$req = $req->fetch(PDO::FETCH_NUM);
		return $req[0];
	}

	/* Mettre à null le champ pays des articles dont le champ id_pays vaut $id_pays
	 * Renvoie un booléen
	 */
	function supprimer_champ_pays($id_pays) {
		$req = 'UPDATE articles SET id_pays = NULL WHERE id_pays = :id_pays';
		$req = $this->bdd->prepare($req);
		$req->bindValue('id_pays', $id_pays, PDO::PARAM_INT);
		$req->execute();
	}

	/* Ajouter un article
	 * Renvoie l'id de l'article créé
	 * Renvoie null en cas d'erreur
	 */
	function ajouter_article($titre, $id_section, $contenu, $id_utilisateur, $id_pays) {
		$req = 'INSERT INTO articles(titre, id_section, contenu, date_publication, id_utilisateur, id_pays) VALUES (:titre, :id_section, :contenu, :date_publication, :id_utilisateur, :id_pays)';
		$req = $this->bdd->prepare($req);
		$req->bindValue('titre', $titre);
		$req->bindValue('id_section', $id_section, PDO::PARAM_INT);
		$req->bindValue('contenu', $contenu);
		$req->bindValue('date_publication', time(), PDO::PARAM_INT);
		$req->bindValue('id_utilisateur', $id_utilisateur, PDO::PARAM_INT);
		$req->bindValue('id_pays', $id_pays, PDO::PARAM_INT);
		$req = $req->execute();

		if ($req)
			$reponse = $this->bdd->lastInsertId();
		else
			$reponse = null;

		return $reponse;
	}

	/* Modifier un article
	 * Renvoie un booléen
	 */
	function modifier_article($id_article, $titre, $contenu, $id_pays) {
		$req = 'UPDATE articles SET titre = :titre, contenu = :contenu, id_pays = :id_pays WHERE id = :id_article';
		$req = $this->bdd->prepare($req);
		$req->bindValue('titre', $titre);
		$req->bindValue('contenu', $contenu);
		$req->bindValue('id_pays', $id_pays, PDO::PARAM_INT);
		$req->bindValue('id_article', $id_article, PDO::PARAM_INT);
		return $req->execute();
	}

	/* Supprimer un article
	 * Renvoie un booléen
	 */
	function supprimer_article($id_article) {
		$req = 'DELETE FROM articles WHERE id = :id';
		$req = $this->bdd->prepare($req);
		$req->bindValue('id', $id, PDO::PARAM_INT);
		return $req->execute();
	}
}