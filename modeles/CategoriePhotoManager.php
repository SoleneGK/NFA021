<?php

class CategoriePhotoManager {
	private $bdd;

	function __construct(PDO $bdd) {
		$this->bdd = $bdd;
	}

	/* Récupérer les informations des différentes catégories
	 * Renvoie un array d'objets CategoriePhoto
	 */
	function obtenir_liste() {
		$req = 'SELECT id, nom, description FROM categories_photos ORDER BY id';
		$req = $this->bdd->prepare($req);
		$req->execute();

		$categories = [];
		foreach ($req->fetchAll(PDO::FETCH_ASSOC) as $categorie) {
			$c = new CategoriePhoto($categorie['id'], $categorie['nom'], $categorie['description']);
			$categories[] = $c;
		}

		return $categories;
	}

	/* Récupérer les informations d'une catégorie
	 * Renvoie un objet Categorie si elle existe
	 * Renvoie false sinon
	 */
	function obtenir_categorie($id) {
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
}