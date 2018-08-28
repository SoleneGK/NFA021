<?php

class ArticleManager {
	public $bdd;

	function __construct($bdd) {
		$this->bdd = $bdd;
	}

	// Ajouter un article (politique ou voyage)
	function ajouter($titre, $id_section, $contenu, $id_utilisateur, $id_pays = null) {
		// Vérifier que le titre et le contenu ne sont pas vides et que la section est correcte
		if (empty($titre))
			$reponse = 'TITRE_VIDE';
		elseif (empty($contenu))
			$reponse = 'CONTENU_VIDE';
		elseif ($id_section != 2 && $id_section != 3)
			$reponse = 'SECTION_INCORRECTE';
		else {
			// Vérifier que l'utilisateur existe
			$req = 'SELECT COUNT(id) FROM utilisateurs WHERE id = :id';
			$req = $this->bdd->prepare($req);
			$req->bindValue('id', $id_utilisateur, PDO::PARAM_INT);
			$req->execute();
			$req = $req->fetch(PDO::FETCH_NUM);

			if ($req[0] == 0)
				$reponse = 'UTILISATEUR_INCONNU';
			else {
				// S'il s'agit d'un article politique ou que le pays n'est pas renseigné, mettre la variable à null
				if ($id_section == 2 || empty($id_pays))
					$id_pays = null;
				// Vérifier que le pays existe
				else {
					$req = 'SELECT COUNT(id) FROM pays WHERE id = :id';
					$req = $this->bdd->prepare($req);
					$req->bindValue('id', $id_pays, PDO::PARAM_INT);
					$req->execute();
					$req = $req->fetch(PDO::FETCH_NUM);

					if ($req[0] == 0)
						$reponse = 'PAYS_INCONNU';
				}

				if (!isset($reponse)) {
				// Enregistrer l'article en bdd
					$req = 'INSERT INTO articles(titre, id_section, contenu, date_publication, id_utilisateur, id_pays) VALUES (:titre, :id_section, :contenu, :date_publication, :id_utilisateur, :id_pays)';
					$req = $this->bdd->prepare($req);
					$req->bindValue('titre', $titre);
					$req->bindValue('id_section', $id_section, PDO::PARAM_INT);
					$req->bindValue('contenu', $contenu);
					$req->bindValue('date_publication', time(), PDO::PARAM_INT);
					$req->bindValue('id_utilisateur', $id_utilisateur, PDO::PARAM_INT);
					$req->bindValue('id_pays', $id_pays, PDO::PARAM_INT);
					$req = $req->execute();

					if($req)
						$reponse = 'OK';
					else
						$reponse = 'ERREUR_CREATION';
				}
			}
		}

		return $reponse;
	}

	// Afficher un article
	function afficher($id) {
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
		var_dump($req);

		// Vérifier qu'un article a été trouvé
		if (!$req)
			$article = false;
		else {
			$u = new Utilisateur($req['id_utilisateur'], $req['pseudo_utilisateur'], null);
			$s = new Section($req['id_section'], $req['nom_section']);
			$p = new Pays($req['id_pays'], $req['nom_pays']);
			$article = new Article($id, $req['titre'], $s, $req['contenu'], $req['date_publication'], $u, $p);
		}

		return $article;
	}

	// Afficher [nombre] articles à partir de [position]
	function afficher_liste_tout($position, $nombre) {
		// Convertir les paramètres en nombre et vérifier qu'ils sont positifs
		$position = (int)$position;
		$nombre = (int)$nombre;
		if ($position < 0 || $nombre < 1)
			$articles = 'BORNES_INCORRECTES';
		else {
			$req = 'SELECT a.id AS id,
						a.titre AS titre,
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
					LIMIT :position, :nombre';
			$req = $this->bdd->prepare($req);
			$req->bindValue('position', $position, PDO::PARAM_INT);
			$req->bindValue('nombre', $nombre, PDO::PARAM_INT);
			$req->execute();

			$articles = [];
			foreach ($req->fetchAll(PDO::FETCH_ASSOC) as $a) {
				$u = new Utilisateur($a['id_utilisateur'], $a['pseudo_utilisateur'], null);
				$s = new Section($a['id_section'], $a['nom_section']);
				$p = new Pays($a['id_pays'], $a['nom_pays']);
				$article = new Article($a['id'], $a['titre'], $s, $a['contenu'], $a['date_publication'], $u, $p);

				$articles[] = $article;
			}
		}

		return $articles;
	}

	// Afficher [nombre] articles à partir de [position] de la section [id_section]
	function afficher_liste_section($position, $nombre, $id_section) {
		// Convertir les paramètres en nombre et vérifier qu'ils sont positifs
		$position = (int)$position;
		$nombre = (int)$nombre;
		if ($position < 0 || $nombre < 1)
			$articles = 'BORNES_INCORRECTES';
		elseif ($id_section != 2 && $id_section != 3)
			$articles = 'SECTION_INCORRECTE';
		else {
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
					LIMIT :position, :nombre';
			$req = $this->bdd->prepare($req);
			$req->bindValue('id_section', $id_section, PDO::PARAM_INT);
			$req->bindValue('position', $position, PDO::PARAM_INT);
			$req->bindValue('nombre', $nombre, PDO::PARAM_INT);
			$req->execute();

			$articles = [];
			foreach ($req->fetchAll(PDO::FETCH_ASSOC) as $a) {
				$u = new Utilisateur($a['id_utilisateur'], $a['pseudo_utilisateur'], null);
				$s = new Section($id_section, $a['nom_section']);
				$p = new Pays($a['id_pays'], $a['nom_pays']);
				$article = new Article($a['id'], $a['titre'], $s, $a['contenu'], $a['date_publication'], $u, $p);

				$articles[] = $article;
			}
		}

		return $articles;
	}

	// Afficher les articles écrits par un utilisateur à partir de [position] de la section [id_section]
	function afficher_liste_utilisateur($position, $nombre, $id_utilisateur) {
		// Convertir les paramètres en nombre et vérifier qu'ils sont positifs
		$position = (int)$position;
		$nombre = (int)$nombre;
		if ($position < 0 || $nombre < 1)
			$articles = 'BORNES_INCORRECTES';
		else {
			$req = 'SELECT a.id AS id,
						a.titre AS titre,
						a.contenu AS contenu,
						a.date_publication AS date_publication,
						u.pseudo AS pseudo_utilisateur,
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
			$req->bindValue('id_utilisateur', $id_utilisateur, PDO::PARAM_INT);
			$req->bindValue('position', $position, PDO::PARAM_INT);
			$req->bindValue('nombre', $nombre, PDO::PARAM_INT);
			$req->execute();

			$articles = [];
			foreach ($req->fetchAll(PDO::FETCH_ASSOC) as $a) {
				$u = new Utilisateur($id_utilisateur, $a['pseudo_utilisateur'], null);
				$s = new Section($a['id_section'], $a['nom_section']);
				$p = new Pays($a['id_pays'], $a['nom_pays']);
				$article = new Article($a['id'], $a['titre'], $s, $a['contenu'], $a['date_publication'], $u, $p);

				$articles[] = $article;
			}
		}

		return $articles;
	}

	// Afficher [nombre] articles à partir de [position] du pays [id_pays]
	function afficher_liste_pays($position, $nombre, $id_pays) {
		// Convertir les paramètres en nombre et vérifier qu'ils sont positifs
		$position = (int)$position;
		$nombre = (int)$nombre;
		if ($position < 0 || $nombre < 1)
			$articles = 'BORNES_INCORRECTES';
		else {
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
					LIMIT :position, :nombre';
			$req = $this->bdd->prepare($req);
			$req->bindValue('id_pays', $id_pays, PDO::PARAM_INT);
			$req->bindValue('position', $position, PDO::PARAM_INT);
			$req->bindValue('nombre', $nombre, PDO::PARAM_INT);
			$req->execute();

			$articles = [];
			foreach ($req->fetchAll(PDO::FETCH_ASSOC) as $a) {
				$u = new Utilisateur($a['id_utilisateur'], $a['pseudo_utilisateur'], null);
				$s = new Section(3, 'Voyages');
				$p = new Pays($id_pays, $a['nom_pays']);
				$article = new Article($a['id'], $a['titre'], $s, $a['contenu'], $a['date_publication'], $u, $p);

				$articles[] = $article;
			}
		}

		return $articles;
	}

	// Modifier le titre ou le contenu d'un article
	function modifier_titre_ou_contenu ($id, $titre, $contenu) {
		// Vérifier que le titre et le contenu de l'article ne sont pas vides
		if (empty($titre))
			$reponse = 'TITRE_VIDE';
		elseif (empty($contenu))
			$reponse = 'CONTENU_VIDE';
		else {
			$req = 'UPDATE articles SET titre = :titre, contenu = :contenu WHERE id = :id';
			$req = $this->bdd->prepare($req);
			$req->bindValue('titre', $titre);
			$req->bindValue('contenu', $contenu);
			$req->bindValue('id', $id, PDO::PARAM_INT);
			$resultat = $req->execute();

			if ($resultat) {
				if ($req->rowCount() == 1)
					$reponse = 'OK';
				else
					$reponse = 'AUCUNE_LIGNE_MODIFIEE';
			}
			else
				$reponse = 'ERREUR_MODIFICATION';
		}

		return $reponse;
	}

	function modifier_pays ($id, $id_pays) {
		// Vérifier que le pays existe
		$req = 'SELECT COUNT(id) FROM pays WHERE id = :id';
		$req = $this->bdd->prepare($req);
		$req->bindValue('id', $id_pays, PDO::PARAM_INT);
		$req->execute();
		$req = $req->fetch(PDO::FETCH_NUM);

		if ($req[0] == 0)
			$reponse = 'PAYS_INCONNU';
		else {
			// Modifier l'article, en vérifiant qu'il appartient bien à la catégorie Voyages (id_section = 3)
			$req = 'UPDATE articles SET id_pays = :id_pays WHERE id = :id AND id_section = 3';
			$req = $this->bdd->prepare($req);
			$req->bindValue('id_pays', $id_pays, PDO::PARAM_INT);
			$req->bindValue('id', $id, PDO::PARAM_INT);
			$resultat = $req->execute();

			if ($resultat) {
				if ($req->rowCount() == 1)
					$reponse = 'OK';
				else
					$reponse = 'AUCUNE_LIGNE_MODIFIEE';
			}
			else
				$reponse = 'ERREUR_MODIFICATION';
		}

		return $reponse;
	}

	// Supprimer un article
	function supprimer ($id) {
		$req = 'DELETE FROM articles WHERE id = :id';
		$req = $this->bdd->prepare($req);
		$req->bindValue('id', $id, PDO::PARAM_INT);
		$resultat = $req->execute();

		if ($resultat) {
			if ($req->rowCount() == 1)
				$reponse = 'OK';
			else
				$reponse = 'AUCUNE_LIGNE_SUPPRIMEE';
		}
		else
			$reponse = 'ERREUR_SUPPRESSION';

		return $reponse;
	}
}