<?php

class PhotoManager {
	private $bdd;

	function __construct(PDO $bdd) {
		$this->bdd = $bdd;
	}

	/* Obtenir les informations d'une photo
	 * Renvoie un objet Photo si elle existe
	 * Renvoie false sinon
	 */
	function obtenir_photo($id) {
		$req = 'SELECT p.titre AS titre,
					p.nom_fichier AS nom_fichier,
					p.description AS description,
					p.date_ajout AS date_ajout,
					c.id AS id_categorie,
					c.nom AS nom_categorie,
					c.description AS description_categorie,
					u.id AS id_utilisateur,
					u.pseudo AS pseudo_utilisateur,
					u.mail AS mail_utilisateur
				FROM photos AS p
				JOIN utilisateurs AS u ON p.id_utilisateur = u.id
				LEFT JOIN categories_photos AS c ON p.id_categorie = c.id
				WHERE p.id = :id';
		$req = $this->bdd->prepare($req);
		$req->bindValue('id', $id, PDO::PARAM_INT);
		$req->execute();
		$req = $req->fetch(PDO::FETCH_ASSOC);

		if(!$req)
			$photo = false;
		else {
			$u = new Utilisateur($req['id_utilisateur'], $req['pseudo_utilisateur'], $req['mail_utilisateur']);
			$c = new CategoriePhoto($req['id_categorie'], $req['nom_categorie'], $req['description_categorie']);
			$photo = new Photo($id, $req['titre'], $u, $req['nom_fichier'], $c, $req['description'], $req['date_ajout']);
		}

		return $photo;
	}

	/* Obtenir les informations des photos appartenant à une catégorie
	 * Renvoie un array
	 */
	function obtenir_photos_categorie(CategoriePhoto $categorie) {
		$req = 'SELECT p.id AS id,
					p.titre AS titre,
					p.nom_fichier AS nom_fichier,
					p.description AS description,
					p.date_ajout AS date_ajout,
					u.id AS id_utilisateur,
					u.pseudo AS pseudo_utilisateur,
					u.mail AS mail_utilisateur
				FROM photos AS p
				JOIN utilisateurs AS u ON p.id_utilisateur = u.id
				JOIN categories_photos AS c ON p.id_categorie = c.id
				ORDER BY p.id
				WHERE c.id = :id';
		$req = $this->bdd->prepare($req);
		$req->bindValue('id', $categorie->id, PDO::PARAM_INT);
		$req->execute();

		$photos = [];
		foreach ($req->fetchAll(PDO::FETCH_ASSOC) as $photo) {
			$u = new Utilisateur($photo['id_utilisateur'], $photo['pseudo_utilisateur'], $photo['mail_utilisateur']);
			$p = new Photo($photo['id'], $photo['titre'], $u, $photo['nom_fichier'], $categorie, $photo['description'], $photo['date_ajout']);

			$photos[] = $p;
		}

		return $photos;
	}
}