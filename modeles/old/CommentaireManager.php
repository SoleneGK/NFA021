<?php

class CommentaireManager {
	public $bdd;

	function __construct($bdd) {
		$this->bdd = $bdd;
	}

	/* Ajouter un commentaire
	 * Soit un utilisateur est connecté, auquel cas son id est utilisée, soit un pseudo doit être fourni par l'internaute
	 */
	function ajouter($id_utilisateur, $pseudo, $mail, $id_article, $contenu) {
		// Vérifier que le contenu du commentaire n'est pas vide
		if (empty($contenu))
			$reponse = 'CONTENU_VIDE';
		else {
			// S'il n'y a pas d'utilisateur connecté, vérifier que le pseudo est fourni
			if (empty($id_utilisateur)) {
				$id_utilisateur = null;
				if (empty($pseudo))
					$reponse = 'PSEUDO_VIDE';
			}
			// Si un utilisateur est connecté, vérifier qu'il existe
			else {
				$req = 'SELECT pseudo FROM utilisateurs WHERE id = :id';
				$req = $this->bdd->prepare($req);
				$req->bindValue('id', $id_utilisateur, PDO::PARAM_INT);
				$req->execute();
				$req = $req->fetch(PDO::FETCH_ASSOC);

				if (!$req)
					$reponse = 'UTILISATEUR_INCONNU';
				else {
					$pseudo = $req['pseudo'];
					$mail = '';
				}
			}

			// Si les informations sur la personne qui a posté le commentaire sont correctes, suite des vérifications
			if (!isset($reponse)) {
				// Vérifier que l'article existe
				$req = 'SELECT COUNT(id) FROM articles WHERE id = :id';
				$req = $this->bdd->prepare($req);
				$req->bindValue('id', $id_article, PDO::PARAM_INT);
				$req->execute();
				$req = $req->fetch(PDO::FETCH_NUM);

				if ($req[0] == 0)
					$reponse = 'ARTICLE_INCONNU';
				else {
					// Ajout du commentaire en bdd
					$req = 'INSERT INTO commentaires(id_utilisateur, pseudo, mail, id_article, contenu, date_ajout) VALUES (:id_utilisateur, :pseudo, :mail, :id_article, :contenu, :date_ajout)';
					$req = $this->bdd->prepare($req);
					$req->bindValue('id_utilisateur', $id_utilisateur, PDO::PARAM_INT);
					$req->bindValue('pseudo', $pseudo);
					$req->bindValue('mail', $mail);
					$req->bindValue('id_article', $id_article, PDO::PARAM_INT);
					$req->bindValue('contenu', $contenu);
					$req->bindValue('date_ajout', time(), PDO::PARAM_INT);
					$req = $req->execute();

					if ($req)
						$reponse = $this->bdd->lastInsertId();
					else
						$reponse = 'ERREUR_CREATION';
				}
			}
		}

		return $reponse;
	}

	// Afficher un commentaire en fonction de son id
	function afficher($id) {
		$req = 'SELECT c.id_utilisateur AS id_utilisateur,
					c.pseudo AS pseudo,
					c.mail AS mail,
					c.contenu AS contenu,
					c.date_ajout AS date_ajout,
					a.id AS id_article,
					a.titre AS titre_article,
					s.id AS id_section,
					s.nom AS nom_section
				FROM commentaires AS c
				JOIN articles AS a ON c.id_article = a.id
				JOIN sections_site AS s ON a.id_section = s.id
				WHERE c.id = :id';
		$req = $this->bdd->prepare($req);
		$req->bindValue('id', $id, PDO::PARAM_INT);
		$req->execute();
		$req = $req->fetch(PDO::FETCH_ASSOC);

		// Vérifier qu'un commentaire correspond à cette id
		if (!$req)
			$commentaire = false;
		else {
			$u = new Utilisateur($req['id_utilisateur'], $req['pseudo'], $req['mail']);
			$s = new Section($req['id_section'], $req['nom_section']);
			$a = new Article($req['id_article'], $req['titre_article'], $s);
			$commentaire = new Commentaire($id, $u, $a, $req['contenu'], $req['date_ajout']);
		}

		return $commentaire;
	}

	// Afficher tous les commentaires
	function afficher_tout() {
		$req = 'SELECT c.id AS id,
					c.id_utilisateur AS id_utilisateur,
					c.pseudo AS pseudo,
					c.mail AS mail,
					c.contenu AS contenu,
					c.date_ajout AS date_ajout,
					a.id AS id_article,
					a.titre AS titre_article,
					s.id AS id_section,
					s.nom AS nom_section
				FROM commentaires AS c
				JOIN articles AS a ON c.id_article = a.id
				JOIN sections_site AS s ON a.id_section = s.id';
		$req = $this->bdd->prepare($req);
		$req->execute();
		
		$commentaires = [];
		foreach($req->fetchAll(PDO::FETCH_ASSOC) as $c) {
			$u = new Utilisateur($c['id_utilisateur'], $c['pseudo'], $c['mail']);
			$s = new Section($c['id_section'], $c['nom_section']);
			$a = new Article($c['id_article'], $c['titre_article'], $s);
			$commentaire = new Commentaire($c['id'], $u, $a, $c['contenu'], $c['date_ajout']);

			$commentaires[] = $commentaire;
		}

		return $commentaires;
	}

	// Modifier un commentaire
	function modifier($id, $pseudo, $mail, $contenu) {
		// Vérifier que le pseudo n'est pas vide
		if (empty($pseudo))
			$reponse = 'PSEUDO_VIDE';
		// Vérifier que le contenu n'est pas vide
		elseif (empty($contenu))
			$reponse = 'CONTENU_VIDE';
		else {
			$req = 'UPDATE commentaires SET pseudo = :pseudo, mail = :mail, contenu = :contenu WHERE id = :id';
			$req = $this->bdd->prepare($req);
			$req->bindValue('pseudo', $pseudo);
			$req->bindValue('mail', $mail);
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

	// Supprimer un commentaire
	function supprimer($id) {
		$req = 'DELETE FROM commentaires WHERE id = :id';
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