<?php

class CategoriePhotoManager {
	public $bdd;

	function __construct($bdd) {
		$this->bdd = $bdd;
	}

	// Obtenir les informations sur une catégorie à partir de son id
	function afficher($id) {
		$req = 'SELECT nom, description FROM categories_photos WHERE id = :id';
		$req = $this->bdd->prepare($req);
		$req->bindValue('id', $id, PDO::PARAM_INT);
		$req->execute();
		$req = $req->fetch(PDO::FETCH_ASSOC);

		if(!$req)
			$categorie = false;
		else
			$categorie = new CategoriePhoto($id, $req['nom'], $req['description']);

		return $categorie;
	}

	function afficher_tout() {
		$req = 'SELECT id, nom, description FROM categories_photos';
		$req = $this->bdd->prepare($req);
		$req->execute();

		$categories = [];
		foreach ($req->fetchAll(PDO::FETCH_ASSOC) as $categorie) {
			$c = new CategoriePhoto($categorie['id'], $categorie['nom'], $categorie['description']);
			$categories[] = $c;
		}

		return $categories;
	}

	// Ajouter une catégorie
	function ajouter($nom, $description) {
		if (empty($nom))
			$reponse = 'NOM_VIDE';
		else {
			$req = 'INSERT INTO categories_photos(nom, description) VALUES (:nom, :description)';
			$req = $this->bdd->prepare($req);
			$req->bindValue('nom', $nom);
			$req->bindValue('description', $description);
			$req->execute();

			if (!$req)
				$reponse = 'ERREUR_CREATION';
			else
				$reponse = $this->bdd->lastInsertId();
		}

		return $reponse;
	}

	// Modifier une catégorie
	function modifier($id, $nom, $description) {
		if (empty($nom))
			$reponse = 'NOM_VIDE';
		else {
			$req = 'UPDATE categories_photos SET nom = :nom, description = :description WHERE id = :id';
			$req = $this->bdd->prepare($req);
			$req->bindValue('nom', $nom);
			$req->bindValue('description', $description);
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

	// Supprimer une catégorie, supprimer les photos appartenant à cette catégorie
	function supprimer($id) {
		// Supprimer les photos
		$req = 'DELETE FROM photos WHERE id_categorie = :id';
		$req = $this->bdd->prepare($req);
		$req->bindValue('id', $id, PDO::PARAM_INT);
		$req->execute();

		if (!$req)
			$reponse = 'ERREUR_SUPPRESSION_PHOTOS';
		else {
			$req = 'DELETE FROM categories_photos WHERE id = :id';
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