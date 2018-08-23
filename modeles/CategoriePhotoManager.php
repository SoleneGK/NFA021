<?php

class CategoriePhotoManager {
	public $bdd;

	function __construct() {
		$this->bdd = Bdd::Connexion();
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
		else {
			$categorie = new CategoriePhoto();
			$categorie->id = $id;
			$categorie->nom = $req['nom'];
			$categorie->description = $req['description'];
		}

		return $categorie;
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
			$reponse = $req->execute();

			if ($reponse)
				$reponse = 'OK';
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
			$req->execute();

			if (!$req)
				$reponse = 'ERREUR_SUPPRESSION_CATEGORIE';
			else
				$reponse = 'OK';
		}

		return $reponse;
	}
}