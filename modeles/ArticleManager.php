<?php

class ArticleManager {
	private $bdd;

	function __construct(PDO $bdd) {
		$this->bdd = $bdd;
	}

	/* Obtenir la liste des articles d'un utilisateur
	 * Afficher NOMBRE_ARTICLES_PAR_PAGE à partir du n° $position
	*/
	function afficher_articles_utilisateur(Utilisateur $utilisateur, $position) {
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
}