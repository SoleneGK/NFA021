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
		$req = $this->bdd->prepare($req);
		$req->bindValue('id', $id, PDO::PARAM_INT);
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
					ORDER BY a.id
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
				ORDER BY a.id
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

	/* Obtenir la liste des articles d'un pays
	 * Affiche NOMBRE_ARTICLES_PAR_PAGE à partir du n° $position
	 * Renvoie un array de Article
	 */
	function obtenir_articles_pays($id_pays, $position) {
		$req = 'SELECT a.id AS id,
					a.titre AS titre,
					a.contenu AS contenu,
					a.date_publication AS date_publication,
					u.id AS id_utilisateur,
					u.pseudo AS pseudo_utilisateur,
					p.id AS id_pays,
					p.nom AS nom_pays
				FROM articles AS a
				JOIN utilisateurs AS u ON a.id_utilisateur = u.id
				JOIN pays AS p ON a.id_pays = p.id
				WHERE p.id = :id_pays
				ORDER BY a.id
				LIMIT :position, :nombre';
		$req = $this->bdd->prepare($req);
		$req->bindValue('id_pays', $id_pays, PDO::PARAM_INT);
		$req->bindValue('position', $position, PDO::PARAM_INT);
		$req->bindValue('nombre', NOMBRE_ARTICLES_PAR_PAGE, PDO::PARAM_INT);
		$req->execute();

		$articles = [];
		foreach ($req->fetchAll(PDO::FETCH_ASSOC) as $a) {
			$u = new Utilisateur($a['id_utilisateur'], $a['pseudo_utilisateur'], null);
			$s = new Section(Article::VOYAGE, 'Voyage');
			$p = new Pays($id_pays, $a['nom_pays']);
			$article = new Article($a['id'], $a['titre'], $s, $a['contenu'], $a['date_publication'], $u, $p);

			$articles[] = $article;
		}

		return $articles;
	}
}