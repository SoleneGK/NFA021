<?php

class PaysManager {
	public $bdd;

	function __construct($bdd) {
		$this->bdd = $bdd;
	}

	// Obtenir les informations sur un pays à partir de son id
	function afficher($id) {
		$req = 'SELECT nom FROM pays WHERE id = :id';
		$req = $this->bdd->prepare($req);
		$req->bindValue('id', $id, PDO::PARAM_INT);
		$req->execute();
		$req = $req->fetch(PDO::FETCH_ASSOC);

		if(!$req)
			$pays = false;
		else
			$pays = new Pays($id, $req['nom']);

		return $pays;
	}

	// Afficher la liste de tous les pays
	function afficher_tout() {
		$req = 'SELECT id, nom FROM pays';
		$req = $this->bdd->prepare($req);
		$req->execute();

		$pays = [];
		foreach ($req->fetchAll(PDO::FETCH_ASSOC) as $ligne) {
			$p = new Pays($ligne['id'], $ligne['nom']);
			$pays[] = $p;
		}

		return $pays;
	}

	// Ajouter un pays
	function ajouter($nom) {
		if (empty($nom))
			$reponse = 'NOM_VIDE';
		else {
			$req = 'INSERT INTO pays(nom) VALUES (:nom)';
			$req = $this->bdd->prepare($req);
			$req->bindValue('nom', $nom);
			$req->execute();

			if (!$req)
				$reponse = 'ERREUR_CREATION';
			else
				$reponse = $this->bdd->lastInsertId();
		}

		return $reponse;
	}

	// Modifier un pays
	function modifier($id, $nom) {
		if (empty($nom))
			$reponse = 'NOM_VIDE';
		else {
			$req = 'UPDATE pays SET nom = :nom WHERE id = :id';
			$req = $this->bdd->prepare($req);
			$req->bindValue('nom', $nom);
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

	// Supprimer un pays, mettre l'id_pays des articles de ce pays à null
	function supprimer($id) {
		// Modifier les articles
		$req = 'UPDATE articles SET id_pays = NULL WHERE id_pays = :id';
		$req = $this->bdd->prepare($req);
		$req->bindValue('id', $id, PDO::PARAM_INT);
		$req->execute();

		if (!$req)
			$reponse = 'ERREUR_MODIFICATION_ARTICLES';
		else {
			$req = 'DELETE FROM pays WHERE id = :id';
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
		}

		return $reponse;
	}
}